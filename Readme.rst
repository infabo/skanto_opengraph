Skanto Open Graph Service
==========================


Features
--------
TYPO3 Extension to include open graph metadata to your TYPO3 website

Install
-------
Install the extension via the TYPO3 extension manager.

By default there is no output of the og: meta tags. You must include the tag rendering function to your site's typoscript template:

::

 page.headerData.8810 = USER
 page.headerData.8810.userFunc = Skanto\SkantoOpengraph\Service\OpenGraphService->getHtml
 

The optional extension *less_static_info* is highly recommendet for automatic file type recognition by filename. The extension less_static_info needs no configuration. Just run the "Import MIME definitions" Scheduler at least once.
Otherwise the automatic recognition of image types is limited to the image types jpg, gif and png. 

Adding Open Graph data to the site
----------------------------------
#) The global site name and maximum image size (for tt_content images) is defined by typoscript constants:
   ::
  
    plugin.tx_skantoopengraph {
      settings {
        maxImageWidth = 1500
        maxImageHeight = 1500
        minImageWidth = 200
        minImageHeight = 200
		
        siteName = my site name
      }
    }

#) After installation you will find a new Tab "Open Graph" inside the page properties of each page and pages_language_overlay. There you can define a Open Graph Title and Description for each page separately. If the open graph fields are left empty the title will be taken from page title and subtitle and the description will be taken from the page's abstract or meta description.

#) When using css_styled_content for content rendering all content elements of type image are automatically added. Otherwise you have to add open graph meta data by this function:
   ::
   
    \Skanto\SkantoOpengraph\Service\OpenGraphService->addUrl('http://my/cool/stuff.ext', array(
	  'mime' => ...,
	  'width' => ...,
	  'height' => ...
    ));

   You can pass in a conf array for more features e.g. overriding mime-type or adding width and height to image and video types:
   ::
   
    addUrl('http://my/cool/stuff.ext', array('mime'=>'my/special-mime'));

   MIME types are automatically set from the given filename when the optional extension *less_static_info* is installed.

Credits
--------
Credits go to Niall Kennedy for his open graph protocol classes at github_.

.. _github: https://github.com/niallkennedy/open-graph-protocol-tools
