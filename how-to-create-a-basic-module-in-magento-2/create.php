<?php
//http://inchoo.net/magento-2/how-to-create-a-basic-module-in-magento-2/

$ds = DIRECTORY_SEPARATOR;

//We’re going to build a very simple module in Magento 2.
//When finished, the module’s output will say “Hello world!”
//in the block content on a custom frontend route.

//Prerequisites

//Needless to say, you will need the latest Magento 2 version which is currently 2.1.
//If you need any help with the Magento 2 installation we have a great article regarding
//this particular topic “How to install Magento 2”.

//Before we start a Magento 2 module development, there are two things people
//often forget and we recommend you to do:

//1. Disable Magento cache

//Disabling Magento cache during development will save you some time because you
//won’t need to manually flush the cache every time you make changes to your code.

//The easiest way to disable cache is to go to 
//Admin → System → Cache Management → select all cache types and disable them.

//2. Put Magento into a developer mode

//You should put Magento into a developer mode to ensure that you see all the
//errors Magento is throwing at you.

//In order to do this, open your terminal and go to the Magento 2 root.
//From there you should run the following command:

//php bin/magento deploy:mode:set developer


//Creating the module files and folders
//Module setup

//1. Create the following folders:
// - app/code/Inchoo
// - app/code/Inchoo/Helloworld
//
//The Incho folder is the module's namespace, and Helloworld is the module's name.
//
//Note: If you don’t have the code folder in your app directory, create it manually.

$namespace      = "Inchoo";
$module_name    = "Helloworld";
$module_version = "1.0.0";
$front_name_id  = "helloworld";
$front_name     = "helloworld";
$block          = "Helloworld";

//2. Now that we have a module folder, we need to create a module.xml file in the app/code/Inchoo/Helloworld/etc folder with the following code:

$module_xml  = "<?xml version=\"1.0\"?>\n";
$module_xml .= "\n"; 
$module_xml .= "<config xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"urn:magento:framework:Module/etc/module.xsd\">\n";
$module_xml .= "\t<module name=\"".$namespace."_".$module_name."\" setup_version=\"".$module_version."\">\n";
$module_xml .= "\t\t</module>\n";
$module_xml .= "</config>";

//3. To register the module, create a registration.php file in the app/code/Inchoo/Helloworld folder with the following code:

$registration_php = "<?php\n";
$registration_php .= "\n"; 
$registration_php .= "\Magento\Framework\Component\ComponentRegistrar::register(\n";
$registration_php .= "\t\Magento\Framework\Component\ComponentRegistrar::MODULE,\n";
$registration_php .= "\t'".$namespace."_".$module_name."',\n";
$registration_php .= "\t__DIR__\n";
$registration_php .= ");";

//4. Open your terminal and go to the Magento 2 root. Run from there the following command:

//php bin/magento setup:upgrade

//If you want to make sure that the module is installed,
//you can go to Admin → Stores → Configuration → Advanced → Advanced
//and check that the module is present in the list or you can open 
//app/etc/config.php and check the array for the ‘Inchoo_Helloworld’ key,
//whose value should be set to 1.


//Creating a controller

//1. First we need to define the router. To do this, create a routes.xml file
//in the app/code/Inchoo/Helloworld/etc/frontend folder with the following code:

$routes_xml  = "<?xml version=\"1.0\"?>\n";
$routes_xml .= "\n";
$routes_xml .= "<config xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"urn:magento:framework:App/etc/routes.xsd\">\n";
$routes_xml .= "\t<router id=\"standard\">\n";
$routes_xml .= "\t\t<route id=\"".$front_name_id."\" frontName=\"".$front_name."\">\n";
$routes_xml .= "\t\t\t<module name=\"".$namespace."_".$module_name."\" />\n";
$routes_xml .= "\t\t</route>\n";
$routes_xml .= "\t</router>\n";
$routes_xml .= "</config>\n";

//Here we’re defining our frontend router and route with an id “helloworld”.

//The frontName attribute is going to be the first part of our URL.

//In Magento 2 URL’s are constructed this way:
//<frontName>/<controler_folder_name>/<controller_class_name>

//So in our example, the final URL will look like this:

//helloworld/index/index

//2. Now we create the Index.php controller file in the
//app/code/Inchoo/Helloworld/Controller/Index
//folder with the following code:

$controller_index_php  = "<?php\n";
$controller_index_php .= "\n";
$controller_index_php .= "namespace ".$namespace."\\".$module_name."\\Controller\\Index;\n";
$controller_index_php .= "\n";
$controller_index_php .= "use Magento\Framework\App\Action\Context;\n";
$controller_index_php .= "\n";
$controller_index_php .= "class Index extends \Magento\Framework\App\Action\Action\n";
$controller_index_php .= "{\n";
$controller_index_php .= "\tprotected \$_resultPageFactory;\n";
$controller_index_php .= "\n";
$controller_index_php .= "\tpublic function __construct(Context \$context, \Magento\Framework\View\Result\PageFactory \$resultPageFactory)\n";
$controller_index_php .= "\t{\n";
$controller_index_php .= "\t\t\$this->_resultPageFactory = \$resultPageFactory;\n";
$controller_index_php .= "\t\tparent::__construct(\$context);\n";
$controller_index_php .= "\t}\n";
$controller_index_php .= "\n";
$controller_index_php .= "\tpublic function execute()\n";
$controller_index_php .= "\t{\n";
$controller_index_php .= "\t\t\$resultPage = \$this->_resultPageFactory->create();\n";
$controller_index_php .= "\t\treturn \$resultPage;\n";
$controller_index_php .= "\t}\n";
$controller_index_php .= "}";

//In Magento 1 each controller can have multiple actions, but in Magento 2 this is not the case.
//In Magento 2 every action has its own class which implements the execute() method

//Creating a block
//
//We’ll create a simple block class with the getHelloWorldTxt() method which returns the “Hello world” string.
//
//1. Create a Helloworld.php file in the app/code/Inchoo/Helloworld/Block folder with the following code:

$block_hello_word_php  = "<?php\n";
$block_hello_word_php .= "namespace ".$namespace."\\".$module_name."\\Block;\n";
$block_hello_word_php .= "\n";
$block_hello_word_php .= "class ".$block." extends \Magento\Framework\View\Element\Template\n";
$block_hello_word_php .= "{\n";
$block_hello_word_php .= "\tpublic function getHelloWorldTxt()\n";
$block_hello_word_php .= "\t{\n";
$block_hello_word_php .= "\t\treturn 'Hello world!';\n";
$block_hello_word_php .= "\t}\n";
$block_hello_word_php .= "}";

//Creating a layout and template files
//
//In Magento 2, layout files and templates are placed in the view folder inside your module.
//Inside the view folder, we can have three subfolders: adminhtml, base and frontend.
//The adminhtml folder is used for admin, the frontend folder is used for frontend
//and the base folder is used for both, admin and frontend files.

//1. First we will create a helloworld_index_index.xml file in the
//app/code/Inchoo/Helloworld/view/frontend/layout folder with the following code:

$index_index_xml  = "<page xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"../../../../../../../lib/internal/Magento/Framework/View/Layout/etc/page_configuration.xsd\" layout=\"1column\">\n";
$index_index_xml .= "\t<body>\n";
$index_index_xml .= "\t\t<referenceContainer name=\"content\">\n";
$index_index_xml .= "\t\t\t<block class=\"".$namespace."\\".$module_name."\\Block\\".$block."\" name=\"".strtolower($block)."\" template=\"".strtolower($block).".phtml\" />\n";
$index_index_xml .= "\t\t</referenceContainer>\n";
$index_index_xml .= "\t</body>\n";
$index_index_xml .= "</page>\n";

//Every page has a layout hand and for our controller action the layout handle is
//helloworld_index_index. You can create a layout configuration file for every layout handle.
//
//In our layout file we have added a block to the content container and set the template
//of our block to helloworld.phtml, which we will create in the next step.

//2. Create a helloworld.phtml file in the app/code/Inchoo/Helloworld/view/frontend/templates
//folder with the following code:

$hello_world_phtml = "<h1><?php echo \$this->getHelloWorldTxt(); ?></h1>";

//$this variable is refrencing our block class and we are calling the method getHelloWorldTxt() which is returning the string ‘Hello world!’.
//
//And that’s it. Open the /helloworld/index/index URL in your browser and you should get something like this:

//--------- --------- ---------

// Gewünschte Verzeichnisstruktur
$dirs = array();
$dirs['namespace']   = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds;
$dirs['namespace_module_name'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds;
$dirs['namespace_module_name_etc']  = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'etc';
$dirs['namespace__module_name_etc_frontend']  = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'etc'.$ds.'frontend';
$dirs['namespace_module_name_controller_index'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'Controller'.$ds.'Index';
$dirs['namespace_module_name_block'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'Block';
$dirs['namespace_module_name_view_Frontend_layout'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'view'.$ds.'frontend'.$ds.'layout';
$dirs['namespace_module_name_view_Frontend_templates'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'view'.$ds.'frontend'.$ds.'templates';

$files = array();
$files['namespace_modulename_etc_module_xml']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'etc'.$ds.'module.xml';
$files['namespace_modulename_etc_module_xml']['content']  = $module_xml;

$files['namespace_module_name_registration_php']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'registration.php';
$files['namespace_module_name_registration_php']['content']  = $registration_php;

$files['namespace_module_name_etc_frontend_routes_xml']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'etc'.$ds.'frontend'.$ds.'routes.xml';
$files['namespace_module_name_etc_frontend_routes_xml']['content']  = $module_xml;

$files['namespace_module_name_controller_index_php']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'Controller'.$ds.'Index'.$ds.'Index.php';
$files['namespace_module_name_controller_index_php']['content']  = $controller_index_php;

$files['module_name_block_hello_world_php']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'Block'.$ds.$block.'.php';
$files['module_name_block_hello_world_php']['content'] = $block_hello_word_php;

$files['namespace_module_name_view_Frontend_layout']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'view'.$ds.'frontend'.$ds.'layout'.$ds.'helloworld_index_index.xml';
$files['namespace_module_name_view_Frontend_layout']['content'] = $index_index_xml;

$files['namespace_module_name_view_Frontend_templates']['filename'] = '.'.$ds.'app'.$ds.'code.'.$ds.$namespace.$ds.$module_name.$ds.'view'.$ds.'frontend'.$ds.'templates'.$ds.strtolower($block).'.phtml';
$files['namespace_module_name_view_Frontend_templates']['content'] = $hello_world_phtml;

// Zur Erstellung der verschachtelten Struktur muss der $recursive-Parameter 
// von mkdir() angegeben werden

foreach($dirs as $key=>$dir){
	if (!file_exists($dir) && !mkdir($dir, 0777, true)) {
		die('Erstellung der Verzeichnisse schlug fehl...');
	}	
}

foreach($files as $key=>$file){
	file_put_contents($file['filename'],$file['content']);	
}
