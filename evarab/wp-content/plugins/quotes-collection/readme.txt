=== Quotes Collection ===
Contributors: SriniG
Donate link: http://srinig.com/wordpress/plugins/quotes-collection/#donate
Tags: quotes collection, quotes, quotations, random quote, sidebar, widget, ajax
Requires at least: 2.1
Tested up to: 2.7.1
Stable tag: trunk

Quotes Collection plugin with Ajax powered Random Quote sidebar widget helps you collect and display your favourite quotes on your WordPress blog.

== Description ==

Quotes Collection plugin with Ajax powered Random Quote sidebar widget helps you collect, manage and display your favourite quotations on your WordPress blog. All quotes or a set of quotes can also be displayed on a page using a `[quote]` tag.

Main features and notes:

* Random Quote sidebar widget with Ajax refresh feature -- you will be able to get another random quote on the same space without refreshing the web page. This refresh feature can be optionally turned off. The widget also comes with few other options in the widget control panel.
* A nice admin interface to add, edit and manage quotes.
* Additional information that can be provided along with the quote: quote author, source (e.g., a book, or a website URL), tags (keywords) and visibility.
* Quotes can be displayed in a page by placing a piece of code (quick tags) such as the ones below.
	* Placing `[quote|all]` in the page displays all quotes.
	* `[quote|author=Somebody]` displays quotes authored by Somebody.
	* `[quote|source=Something]` displays quotes with source as ‘Something’
	* `[quote|tags=sometag]` displays quotes tagged sometag 
	* `[quote|tags=tag1,tag2,tag3]` displays quotes tagged tag1 or tag2 or tag3, one or more or all of these
	* `[quote|id=3]` displays quote with ID 3
	* `[quote|random]` displays a random quote
* The template function `quotescollection_display_randomquote()` can be used to display a random quote in places other than sidebar.
* Support for Localization. As of version 1.2.8, translation is available in the following languages.
	* Arabic
	* Bosnian
	* Danish
	* German
	* Spanish
	* Persian
	* French
	* Croatian
	* Italian
	* Japanese
	* Dutch
	* Polish
	* Portugese
	* Russian
	* Serbian
	* Swedish
	* Tamil
	* Turkish
	* Ukrainian

== Installation ==

1. Unzip and upload the `quotes-collection` directory to the `/wp-content/plugins/` directory
1. Go to `WP Admin » Plugins` and activate the ‘Quotes Collection’ plugin
1. To add and manage the quotes go to `WP Admin » Manage » Quotes Collection`
1. To display a random quote in the sidebar, go to `WP Admin » Presentation » Widgets`, drag ‘Random Quote’ widget into the sidebar

== Frequently Asked Questions ==

= How to get rid of the quotation marks that surround the random quote? =

Open the quotes-collection.css file that comes along with the plugin, scroll down and look towards the bottom.

= The 'Next quote »' link is not working. Why? =

You have to check a couple of things,

1. Make sure your theme’s header.php file has the code `<?php wp_head(); ?>` just before `</head>`.

2. Make sure the plugin files are uploaded in the correct location. The files should be uploaded in a location as follows
<pre>	wp-content/
	|-- plugins/
		|-- quotes-collection/
    		|-- quotes-collection.php
    		|-- quotes-collection.js
    		|-- quotes-collection.css
    		|-- quotes-collection-ajax.php</pre>
        
If you still experience the problem even after the above conditions are met, [contact](http://srinig.com/contact/) the plugin author.


= How to hide the 'Next quote »' link? = 

You can do this by turning off the 'Ajax Refresh feature' in widget options.

= What are the parameters that can be passed on to  `quotescollection_display_randomquote()` template function? =

Three parameters can be passed into the `quotescollection_display_randomquote()` template function.

* `quotescollection_display_randomquote(1, 1, 1)` will show author, show source and switch on the ajax refresh feature.
* `quotescollection_display_randomquote(0, 0, 0)` will hide author, hide source and switch off the ajax refresh.

You can mix those parameters as you like it. Feel free to experiment. :)

= How about a feature to backup/export/import the bulk of quotes in CSV/text format? =

Such a feature will be available in a future version of the plugin, though no promises can be made as to when it will be available!

= How to change the admin access level setting for the quotes collection admin page? =

Change the value of the variable `$quotescollection_admin_userlevel` on line 16 of the quotes-collection.php file. Refer [WordPress documentation](http://codex.wordpress.org/Roles_and_Capabilities) for more information about user roles and capabilities.

== Screenshots ==

1. Admin interface
2. ‘Random Quote’ widget options
3. Random quote as can be seen in the sidebar of [srinig.com](http://srinig.com/)

