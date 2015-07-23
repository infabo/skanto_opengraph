<?php
namespace Skanto\SkantoOpengraph\Service;

require_once(dirname(__file__) . '/../../Resources/Library/OpenGraphProtocolTools/open-graph-protocol.php');
require_once(dirname(__file__) . '/../../Resources/Library/OpenGraphProtocolTools/media.php');
require_once(dirname(__file__) . '/../../Resources/Library/OpenGraphProtocolTools/objects.php');

class OpenGraphService implements \TYPO3\CMS\Core\SingletonInterface
{

    protected $ogp;
    protected $objectManager;
    protected $configurationManager;
    protected $settings;

    public function __construct()
    {
        // load extension settings
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $this->configurationManager = $this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface');
        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'SkantoOpengraph'
        );
        $this->settings = $frameworkConfiguration['settings'];

        // init objects
        $this->cObj = $this->objectManager->get('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer'); // todo: use object manager
        $this->ogp = new \OpenGraphProtocol();

        // set site attributes
        $siteName = $this->settings['siteName'];
        /*
        if (!$siteName) {
            // load the site name from global config.
            // $siteName = $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];
            // No, that makes no sense. 1) sitename is internal, isn't it?
            //							2) cannot translate.
        }
        */
        $this->ogp->setSiteName($siteName);
        $this->ogp->setType('website');

        // set languages
        $locale = $GLOBALS["TSFE"]->tmpl->setup["config."]["locale_all"]; // @todo really? no better way?
        // why is $GLOBALS['TSFE']->sys_language_isocode empty?
        $this->ogp->setLocale($locale != '' ? $locale : 'en_GB');
        // todo: alternative languages of this page.
        // possible via typoscript -> language menu?

        // set page attributes
        $pageName = $GLOBALS['TSFE']->page['tx_skantoopengraph_title'];
        if (!$pageName) {
            $pageName = $GLOBALS['TSFE']->page['title'];
            if ($GLOBALS['TSFE']->page['subtitle'] != '') {
                $pageName .= ' – ' . $GLOBALS['TSFE']->page['subtitle'];
            }
        }
        $this->ogp->setTitle($pageName);
        $description = $GLOBALS['TSFE']->page['tx_skantoopengraph_description'];
        if (!$description) {
            $description = $GLOBALS['TSFE']->page['abstract'];
        }
        if (!$description) {
            $description = $GLOBALS['TSFE']->page['description'];
        }
        $this->ogp->setDescription($description);

        $this->ogp->setURL($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); // @todo realurl / canonical url?
        // $this->ogp->setDeterminer( 'the' ); // @todo?

    }

    public function getHtml()
    {
        // add the default image
        $this->addUrl($this->settings['defaultImage']);

        // generate html output
        return PHP_EOL . $this->ogp->toHTML() . PHP_EOL;
    }

    public function cssStyledContentAddImage($params, $obj)
    {
        $origPath = $this->cObj->IMG_RESOURCE($params);
        $conf = array(
            'file' => $origPath,
            'file.' => array(
                'maxW' => $this->settings['maxImageWidth'],
                'maxH' => $this->settings['maxImageHeight'],
                'minW' => $this->settings['minImageWidth'],
                'minH' => $this->settings['minImageHeight']
            )
        );
        $this->addUrl($this->cObj->IMG_RESOURCE($conf));

        return false; // rendering of other hooked renderers can do their work.
    }

    public function addUrl($url, $conf = null)
    {
        // get settings from (TypoScript) Array
        // @todo: stdwrap all attributes.
        if ($conf['url']) {
            $url = $conf['url'];
        }
        if ($conf['width']) {
            $width = $conf['width'];
        }
        if ($conf['height']) {
            $height = $conf['height'];
        }

        // decide what type to use
        if ($conf['mime']) {
            $mime = $conf['mime'];
        } else {
            $filename = basename($url);
            $fileext = array_pop(explode('.', $filename));
            $mime = $this->getMimeType($fileext);
        }
        switch ($mime) {
            case 'application/x-shockwave-flash':
                $type = 'image';
                break;
            default:
                $type = array_shift(explode('/', $mime));
        }

        // add ogp object
        switch ($type) {
            case 'image':

                $image = new \OpenGraphProtocolImage();
                $image->setURL($url);
//				$image->setSecureURL( 'https://example.com/image.jpg' );
                $image->setType($mime);
                $image->setWidth($width);
                $image->setHeight($height);

                $this->ogp->addImage($image);
                break;

            case 'video' :

                $video = new \OpenGraphProtocolVideo();
                $video->setURL($url);
//				$video->setSecureURL( 'https://example.com/video.swf' );
                $video->setType($mime);
                $video->setWidth($width);
                $video->setHeight($height);

                $this->ogp->addVideo($video);
                break;

            case 'audio':

                $audio = new \OpenGraphProtocolAudio();
                $audio->setURL($url);
//				$audio->setSecureURL( 'https://example.com/audio.mp3' );
                $audio->setType($mime);

                $this->ogp->addAudio($audio);
                break;

        }
    }

    public function getMimeType($ext)
    {
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('less_static_info')) {
            if (!isset($this->fileextRepository)) {
                $this->fileextRepository = $this->objectManager->get('Skanto\LessStaticInfo\Domain\Repository\FileextRepository');
            }
            $mimeObj = $this->fileextRepository->findOneByFileext($ext);
            if ($mimeObj) {
                $mime = $mimeObj->getMime();
            }
        }

        if (!$mime) {
            switch (strtolower($ext)) {
                case 'jpeg':
                case 'jpg':
                    $mime = 'image/jpeg';
                    break;
                case 'png':
                    $mime = 'image/png';
                    break;
                case 'gif':
                    $mime = 'image/gif';
                    break;
                default:
                    $mime = '';
            }
        }

        return $mime;
    }
}