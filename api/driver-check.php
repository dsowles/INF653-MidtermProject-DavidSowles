<?php
/**
 * Project: INF653 Midterm
 * Filename: driver-check.php
 * Author: David A. Sowles
 * Creation Date: 03/26/2026
 * Last Updated: 03/31/2026
 * 
 * Description: This is a short php script for testing whether
 *              the system has the right drivers for the database stuff.
 */


echo "Available PDO Drivers: <br>";
print_r(PDO::getAvailableDrivers());