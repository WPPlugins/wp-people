<?php 
/*
Plugin Name: WP People
Plugin URI: http://www.dean-logan.com/plugins-and-widgets/
Description:  This filter finds names from the XFN Links database and creates a link to learn more information about the person. You can view the people selected through the <a href="tools.php?page=wp-people">WP People</a> link in the <a href="tools.php">Tools</a> area.
Version: 3.01
Author: Dean Logan
Author URI: http://www.dean-logan.com
*/
/**  Copyright 2006-2009  Dean Logan  (email : wp-dev@dean-logan.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

To view a copy of the GNU General Public License
go to <http://www.gnu.org/licenses/>.
**/
	$cat_type = "link_category";
	$cat_name = "WP People";
	$blogSiteURL = get_option('siteurl');
	
	function printAddBox()
	{
		global $blogSiteURL;
				
		?><h2>WP People</h2>
		 <p>WP People uses the <a href="<?php echo $blogSiteURL; ?>/wp-admin/link-manager.php" /> Links XFN table</a>. <br />
		 It will search the table for any names that have the WP People links category. <br />
		 To add a person to the WP People you just add the WP People link cateogry to their Link.</p>
		 The field on the Links form match up the following way: <br />
		 <ul>
		 <li><strong>Name</strong> is the <strong>Real Name (searched name)</strong> in WP People</li>
		 <li><strong>Description</strong> is the <strong>Nick Name (displayed name)</strong> in WP People</li>
		 <li><strong>Web Address</strong> is the <strong>Link</strong> in WP People</li>
		 <li><strong><em>Advanced</em> Notes</strong> is the <strong>Description/Bio</strong> in WP People</li>
		 <li><strong><em>Advanced</em> Image Link</strong> is the <strong>Photo</strong> in WP People</li>
		 </ul>
		 <?php
	}
	
	function printMessageBox($messageString)
	{ 
		?>
		<div id="messageBox" class="error"><strong><?php echo $messageString; ?></strong></div>
		<?php
	}

	/* This creates the edit form */
	function printEditBox($thisId, $thisName, $thisNickname, $thisBio, $thisUrl, $thisPhoto, $thisPhotoTitle)
	{
		global $blogSiteURL;
		?>
		<br />
		<h2>WP People Edit</h2>	
		<form name="editBio" action="<?php echo $PHP_SELF; ?>?page=wp-people" method=POST>
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="wppeople_id" value="<?php echo $thisId; ?>">
			<table border="0" cellpadding="2" cellspacing="0" align="center" class="you-profile">
			<tr>
				<td colspan="3"><p>Use this form to view the user's name and details.<br>
				If you have moved this person to the <a href="<?php echo $blogSiteURL; ?>/wp-admin/link-manager.php" /> Links XFN table</a>,
				then use the delete button to remove the person from your listing.</p></td>
			</tr>
			<tr>
				<td width="110" align="right" class="labels">Name:</td>
				<td colspan="2"><input type="text" name="wpp_name" value="<?php echo $thisName; ?>" size="40"></td>
			</tr>
			<tr>
				<td width="110" align="right" class="labels">Nickname:</td>
				<td colspan="2"><input type="text" name="wpp_nickname" value="<?php echo $thisNickname; ?>" size="40"></td>
			</tr>
			<tr>
				<td align="right" valign="top"><span class="labels">Bio:</span> </td>
				<td><textarea name="wpp_bio" rows=15 cols=50><?php echo $thisBio; ?></textarea></td>
				<td width="78" rowspan="5" align="center" valign="top"><?php    
					if (!$thisPhoto){
						$thisPhoto = "/images/nophoto.jpg";
						$thisPhotoTitle = "no photo";
					}
					?>
				  <span class="labels" style="font-size: 8pt;">(100 x 100)</span><br/>
					<div class="shadow">
					<img src="<?php echo $thisPhoto ?>" title="<?php echo $thisPhotoTitle ?>" alt="<?php echo $thisPhotoTitle ?>" width="100" height="100" />
					<span class="labels" style="font-size: 8pt;"><?php echo $thisPhotoTitle ?></span>
					</div>
			  </td>
			</tr>
			<tr>
				<td align="right" class="labels">URL:</td>
				<td width="441"><input type="text" name="wpp_url" value="<?php echo $thisUrl; ?>" size="50"></td>
			  </tr> 
			<tr>
				<td width="110" align="right" class="labels">Photo:</td>
				<td><input type="text" name="wpp_img_url" value="<?php echo $thisPhoto; ?>" size="40"></td>
			</tr>
			<tr>
				<td width="110" align="right" class="labels">Photo Title:</td>
				<td><input type="text" name="wpp_img_title" value="<?php echo $thisPhotoTitle; ?>" size="40"></td>
			</tr>
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">
				<input type="submit" name="wppeople_action" value="<?php _e('Back', 'default') ?>" />
				<input type="submit" name="wppeople_action" value="<?php _e('Delete', 'delete') ?>" onclick="if (confirm ('Are you sure you want to delete person \'' + editPeople.id.options[editPeople.id.selectedIndex].text + '\'?'))"></td>
			</tr>
			</table>
		</form>
	<?
	}
	
	function printLinkPeople($thisUserId, $thisUserLevel) {
		global $wpdb, $cat_name, $cat_type, $blogSiteURL;

		$cat = is_term($cat_name, $cat_type);			
		$links = get_bookmarks("category=" . $cat['term_id']);
		print("<h2>Links WP People List</h2><ul>");
		foreach ($links as $link) {
			$link = sanitize_bookmark($link);
			$link->link_name = attribute_escape($link->link_name);
			$link->link_id = attribute_escape($link->link_id);
			$short_url = str_replace('http://', '', $link->link_url);
			$short_url = preg_replace('/^www./i', '', $short_url);
			if ('/' == substr($short_url, -1))
				$short_url = substr($short_url, 0, -1);
			if (strlen($short_url) > 35)
				$short_url = substr($short_url, 0, 32).'...';
			
			print("<li><a href=\"" . $blogSiteURL . "/wp-admin/link.php?action=edit&link_id=" . $link->link_id . "\" >" . $link->link_name . "</a></li>");
		}
		print("</ul>");
	}
	
	function printPeopleList($thisUserId, $thisUserLevel)
	{
		$dropListString;
		$itemCount = 0;
		global $wpdb;
		$wppeople_table = $wpdb->prefix . "_people";
		
		$sql = "SELECT `people_ID`, `people_name` 
				FROM `" . $wppeople_table . "`";
		if($thisUserLevel != 10)
		{
			$sql .= " WHERE `wpuser_ID` = " . $thisUserId;
		}
		$sql .= " ORDER BY `people_name`;";
		
		$result = $wpdb->get_results($sql, ARRAY_A);
		if($result)
		{
			foreach($result as $resultItem)
			{
				$itemCount++;
				$dropListString .= "<option value=\"" . $resultItem['people_ID'] . "\">" . $resultItem['people_name'] .	"</option> \n"; 
			}
		}
				
		if (0 < $itemCount)
		{
			// create edit list ?>
			<br />
			<h2>WP People List</h2>
			<p>There are currently <b><?php echo $itemCount; ?></b>&nbsp; 
			 persons in your table.</p>
		    <?php
		  	if(10 == $thisUserLevel){
				print("<p>As an administrator you see all WP People records.</p>");
			}
			?>
		  
			<form action="<?php echo $PHP_SELF ?>?page=wp-people"  method="POST" name="editPeople">
			<p>Please select a person's bio to edit or remove:<br />
			<select name="wppeople_id">
			  <?php 
				echo $dropListString; 
				?> 
			</select>
			<input type="submit" name="wppeople_action" value="<?php _e('View', 'view') ?>">
			<input type="submit" name="wppeople_action" value="<?php _e('Delete', 'delete') ?>" onclick="if (confirm ('Are you sure you want to delete this person?'))">			 
			</form>
	<?php 
		}
	}	
	
	function wppeople_edit($thisPeopleId)
	{
		global $wpdb;
		$wppeople_table = $wpdb->prefix . "_people";
		$sql = "SELECT `people_name`, `people_nickname`, `people_bio`, 
				`people_url`, `people_image_url`, `people_image_title_nm`  
			    FROM `" . $wppeople_table . "` 
			    WHERE `people_ID` =". $thisPeopleId . ";";
		$result = $wpdb->get_results($sql, ARRAY_A);
		if($result)	{
			foreach($result as $resultItem)	{
				printEditBox($thisPeopleId, $resultItem['people_name'], $resultItem['people_nickname'], $resultItem['people_bio'], $resultItem['people_url'], $resultItem['people_image_url'], $resultItem['people_image_title_nm']);
			}
		} else {
			printMessageBox("No record found for id " . $thisPeopleId . " in table " . $wppeople_table);
		}
	}
	
	function wppeople_delete($thisPeopleId)
	{
		global $wpdb;
		$wppeople_table = $wpdb->prefix . "_people";
		$sql = "DELETE FROM `" . $wppeople_table . "` " 
			   . " WHERE `people_ID` = ". $thisPeopleId . ";";
		$wpdb->query($sql);
		printMessageBox("WP Person deleted.");
	}
	
	function wppeople_install($doInstall)
	{
		global $wpdb;
		global $user_ID;
		
   		$table_name = $wpdb->prefix . "_people";
		?>
		<br />
		<h2>WP People Install</h2>
		<?php
		
		if ($wpdb->get_var("show tables like '$table_name'") == $table_name) 
		{
			$check1 = mysql_query("SHOW COLUMNS FROM $table_name LIKE 'wpuser_ID'") 
				or die(mysql_error());
			$check2 = mysql_query("SHOW COLUMNS FROM $table_name LIKE 'people_nickname'") 
				or die(mysql_error());	
		}
		
		if($wpdb->get_var("show tables like '$table_name'") != $table_name)
		{
			if($doInstall)
			{
				$sql = "CREATE TABLE `" . $table_name . "` (
				  `people_ID` int(11) NOT NULL auto_increment,
				  `wpuser_ID` bigint(20) NOT NULL default '1',
				  `people_name` varchar(160) NOT NULL default '',
				  `people_nickname` varchar(100) NOT NULL default '',
				  `people_bio` text NOT NULL,
				  `people_url` varchar(255) default NULL,
				  `people_image_url` varchar(255) default NULL,
				  `people_image_title_nm` varchar(160) default NULL,
				  PRIMARY KEY  (`people_ID`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
				require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
				dbDelta($sql);
				
				printMessageBox("Created table " . $table_name . ". <br /><code>" . $sql . "</code>");
			}
			else
			{
				?>
				<p>The WordPress People table (<?php echo $table_name; ?>) was not been found - 
				click the install button to perform the database changes now.</p>
				
				<form action="<?php $PHP_SELF ?>?page=wp-people" method="POST" name="addPeople">
				 <input type="submit" name="wppeople_action" value="<?php _e('Install', 'install') ?>" /><br/> 
				</form> 
				<?php
			}
		}
		else
		{ 
			if (mysql_num_rows($check1) == 0)
			{
				if($doInstall)
				{
					$sql = "ALTER TABLE `" . $table_name . "`";
					$sql .= " ADD `wpuser_ID` BIGINT( 20 ) DEFAULT '". $wpuser_ID ."' NOT NULL AFTER `people_ID`";
					$wpdb->query($sql);			
					printMessageBox("Updated table " . $table_name . ". Add column &quot;wpuser_ID&quot;. <br /><code>" . $sql . "</code>");
				}
				else
				{
					?>
						<p>The WordPress People table is not up-to-date.  You are missing the &quot;wpuser_ID&quot; column.
						<br />click the update button to perform the database changes now.</p>
						<form action="<?php $PHP_SELF ?>?page=wp-people" method="POST" name="addPeople">
						 <input type="submit" name="wppeople_action" value="<?php _e('Update Table', 'install') ?>" /><br/> 
						</form> 
					<?php
				}
			}
			if(mysql_num_rows($check2) == 0)
			{
				if($doInstall)
				{
					$sql = "ALTER TABLE `" . $table_name . "`";
					$sql .= " ADD `people_nickname` VARCHAR( 100 ) NOT NULL AFTER `people_name`";
					$wpdb->query($sql);
					printMessageBox("Updated table " . $table_name . ". Add column &quot;people_nickname&quot;. <br /><code>" . $sql . "</code>");
				}
				else
				{
					?>
						<p>The WordPress People table is not up-to-date.  You are missing the &quot;people_nickname&quot; column.
						<br />click the update button to perform the database changes now.</p>
						<form action="<?php $PHP_SELF ?>?page=wp-people" method="POST" name="addPeople">
						 <input type="submit" name="wppeople_action" value="<?php _e('Update Table', 'install') ?>" /><br/> 
						</form> 
					<?php
				}
			}
		}
	}
	
	function wp_people_admin()
	{
		global $PHP_SELF;
		global $userdata;
		global $wpdb;
		$wppeople_table = $wpdb->prefix . "_people";

      	get_currentuserinfo();	
		$thisUserLevel = $userdata->user_level;
		$thisUserId = $userdata->ID;
		print_r("<div class=\"wrap\">");
		echo $tabletop;
		if(isset($_POST['wppeople_action']))
		{
			$wppaction = $_POST['wppeople_action'];
			//print_r("wppaction = $wppaction");
			switch($wppaction)
			{
				case "add" :
				case "Add" :
				case "Add User" :
				case "delete" :
				case "Delete" :
					$form_wpp_id = $_POST['wppeople_id'];
					wppeople_delete($form_wpp_id);
					
					printPeopleList($thisUserId, $thisUserLevel);
					printAddBox();
					printLinkPeople($thisUserId, $thisUserLevel);
					break;
				case "update" :
				case "Update" :
				case "Save" :
				case "view" :
				case "View" :
					$form_wpp_id = $_POST['wppeople_id'];
					wppeople_edit($form_wpp_id);
					break;
				case "install" :
				case "update table" : 
				case "Update Table" :
				case "Install" :
					wppeople_install(true);
					break;
				default :
					printPeopleList($thisUserId, $thisUserLevel);			
					printAddBox();
					printLinkPeople($thisUserId, $thisUserLevel);
					break;
			}
		}
		else
		{
			if(checkTable())
			{
				//print_r("Display WP People List and Add box");
				printPeopleList($thisUserId, $thisUserLevel);
			}
			printAddBox();
			checkLinkCategory();
			printLinkPeople($thisUserId, $thisUserLevel);
		}
		echo $tablebottom;
		print_r("</div>");
	}


	function wp_people_add_pages()
	{
		if (function_exists('wp_people_admin'))
		{
			add_submenu_page('profile.php', 'WP People', 'WP People', 7, basename(__FILE__), 'wp_people_admin');			
		}
	}	
	
	// mt_add_pages() is the sink function for the 'admin_menu' hook
	function mt_add_pages()
	{
		add_management_page('WP People', 'WP People', 'publish_posts', 'wp-people','wp_people_admin');
	}
	
	// Insert the mt_add_pages() sink into the plugin hook list for 'admin_menu'
	add_action('admin_menu', 'mt_add_pages');
			
	function peopleDefine($text)
	{  	
		global $wpdb, $cat_name, $cat_type, $blogSiteURL;

		$cat = is_term($cat_name, $cat_type);			
		$links = get_bookmarks("category=" . $cat['term_id']);
		
		$patternArray;
		$replaceArray;
		$displayNameArray;
		
		foreach ($links as $link) {
			$link = sanitize_bookmark($link);
			$real_name = $link->link_name = attribute_escape($link->link_name);
			$thisID = $link->link_id = attribute_escape($link->link_id);
			$nickname = $link->link_description = attribute_escape($link->link_description);	
		
			if($nickname == '')
			{
				$display_name = $real_name;
			}
			else
			{
				$display_name = $nickname;
			}
			
			//changes text to the wp-people link
			$replaceText = '<a id="wp-' . $thisID . 'people" href="' . $blogSiteURL;
			$replaceText .= '/wp-content/plugins/wp-people/wp-people-popup.php?person=';
			$replaceText .= $thisID . '" target="' . $blogSiteURL . '/wp-content/plugins/wp-people/wp-people-popup.php?person=';
			$replaceText .= $thisID . '" class="thickbox" >';
			$replaceText .= $display_name . '</a>';
			
			$displayNameText = '<span class="caps" style="color:blue;">' . $display_name . '</span>';
			
			$replaceArray[$nameX] = $replaceText;
			$patternArray[$nameX] = "/$real_name/";
			$displayNameArray[$nameX] = $displayNameText;
			
			$nameX++;
		}
		
		/* debuggin code
		echo "<pre>";
		print_r($replaceArray);
		print_r($patternArray);
		print_r($displayNameArray);
		echo "</pre>";
		*/
		
		$text = preg_replace($patternArray, $replaceArray, $text, 1);
		//formats the text using a span tag and the class of "caps"
		$text = preg_replace($patternArray, $displayNameArray, $text);		
		//"#$real_name(?!</(ac|sp))#i"
		return $text;
	}
	
	function checkLinkCategory() {
		global $wpdb, $cat_name, $cat_type;
		$wpterm_taxonomy_table = $wpdb->prefix . "wp_term_relationships";
		$wpterms_table = $wpdb->prefix . "wp_terms";

		$cat = is_term($cat_name, $cat_type);

		if($cat['term_id'] == null) {
			$catArgs = array('name' => $cat_name, 'slug' => 'wppeople',  'parent' => '', 'description' => 'Tag for adding people to the WP People list.');
			wp_insert_term($cat_name , $cat_type, $catArgs);
			//print("Created the WP People link category");
		}
		
		return true;
	}
	
	function checkTable()
	{
		global $wpdb;
		$wppeople_table = $wpdb->prefix . "_people";
		$tableexists = false;
		
		//check for table
		$sql = "SELECT * FROM `" . $wppeople_table . "`";
		//$result = get_results($sql);
		$result = mysql_query($sql);
		$num_rows = mysql_num_rows($result);
		
		if($num_rows > 0)
		{
			$tableexists = true;
		}
		
		return $tableexists;
	}
	// add the filter
	if(checkLinkCategory()){
		add_filter("the_content", "peopleDefine");
	}
?>