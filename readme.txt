=== WP People ===
Contributors: Dean Logan
Donate link: http://www.dean-logan.com/plugins-and-widgets
Tags: xfn, links, blogroll, people
Requires at least: 2.7.1
Tested up to: 2.7.1
Stable tag: 3.01

This is a filter that will switch out people's names for XFN Links information.

== Description ==

The original author of the hack stopped supporting it a while ago. I took his original idea and used another 
hack (acronymit) as a guide to make this work. The original worked with the my-hacks script used in 
WordPress 1.0.1, so this is beyond the functionality of the original.

This plug-in will search a post and find names that match database records of people maked with the WP Category 
in the XFN Links. When it finds a match, it will replace the name with a link to the person. There is a administration 
screen for adding people and their bios to the database viewing the current people marked for the filter. More than 
one person can be linked on a post. A individual name will only be linked once per post.

If you were using the version 2 of Word Press People, then you will be able to see any current people in 
WP People. I have not added a method to convert the current values to Links. I don’t know how many 
people are using the plug-in, so I didn’t think the extra work to make that happen would be worth it.

== Installation ==
1. Upload `wp-people` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the WP People administration page under the Tools section.  The first time there will create the "WP People" Category
1. Go to the Links administration page. Add new Links or add the WP Category to those people who you want to be included in WP People.
1. Optionally Install the `Wordpress Thickbox` plugin (http://www.christianschenk.org/projects/wordpress-thickbox-plugin/) to be
used as the popup window.
1. Optionally Install the 'WordPress Force Word Wrapping' plugin (http://www.seocompany.ca/seo-blog/wordpress-force-word-wrapping-plugin/) to prevent
the description text from extednig past the popup window area.

== Frequently Asked Questions ==
= What is the field mapping from the Link to WP People? =
The field on the Links form match up the following way:

* Name is the Real Name (searched name) in WP People
* Description is the Nick Name (displayed name) in WP People
* Web Address is the Link in WP People
* Advanced Notes is the Description/Bio in WP People
* Advanced Image Link is the Photo in WP People

= Can I update the style of the popup? =
The popup style sheet is in the WP People folder (wp-people-css.css)

= What size is the image? = 
The image is set at 100px x 100px.  It is best to resize the image to fit this value.

= What if I don't have a photo for the person I am linking? =
Included with WP People is the "nophoto.jpg" file.  Copy this image into the `images` directory of any theme you are using.

== Screenshots ==
No Screen shots