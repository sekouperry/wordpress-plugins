<?php
/*
Plugin Name: Local Time
Description: Displays the visitor's local time
Version: 1.0
Author: Your Name
*/

function display_local_time() {
  // Get the visitor's timezone offset in minutes
  $offset = intval($_GET['tz']);

  // Convert the offset to hours
  $offset_hours = $offset / 60;

  // Get the current time in the visitor's timezone
  $local_time = time() + ($offset_hours * 3600);

  // Format the time as a string
  $local_time_str = date('h:i:s A', $local_time);

  // Display the time
  echo "Your local time is: $local_time_str";
}

// Add a shortcode to display the local time
add_shortcode('local_time', 'display_local_time');
