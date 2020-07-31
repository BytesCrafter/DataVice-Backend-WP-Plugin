<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package datavice-wp-plugin
     * @version 0.1.0
     * Here is where you check if there is an update on our plugin repo for this project.
    */

    $prov_list = "('Ilocos Norte', '0128', 'PH', 0),
        ('Ilocos Sur', '0129', 'PH', 0),
        ('La Union', '0133', 'PH', 0),
        ('Pangasinan', '0155', 'PH', 0),
        ('Batanes', '0209', 'PH', 0),
        ('Cagayan', '0215', 'PH', 0),
        ('Isabela', '0231', 'PH', 0),
        ('Nueva Vizcaya', '0250', 'PH', 0),
        ('Quirino', '0257', 'PH', 0),
        ('Bataan', '0308', 'PH', 0),
        ('Bulacan', '0314', 'PH', 0),
        ('Nueva Ecija', '0349', 'PH', 0),
        ('Pampanga', '0354', 'PH', 0),
        ('Tarlac', '0369', 'PH', 0),
        ('Zambales', '0371', 'PH', 0),
        ('Aurora', '0377', 'PH', 0),
        ('Batangas', '0410', 'PH', 0),
        ('Cavite', '0421', 'PH', 0),
        ('Laguna', '0434', 'PH', 0),
        ('Quezon', '0456', 'PH', 0),
        ('Rizal', '0458', 'PH', 0),
        ('Marinduque', '1740', 'PH', 0),
        ('Occidental Mindoro', '1751', 'PH', 0),
        ('Oriental Mindoro', '1752', 'PH', 0),
        ('Palawan', '1753', 'PH', 0),
        ('Romblon', '1759', 'PH', 0),
        ('Albay', '0505', 'PH', 0),
        ('Camarines Norte', '0516', 'PH', 0),
        ('Camarines Sur', '0517', 'PH', 0),
        ('Catanduanes', '0520', 'PH', 0),
        ('Masbate', '0541', 'PH', 0),
        ('Sorsogon', '0562', 'PH', 0),
        ('Aklan', '0604', 'PH', 0),
        ('Antique', '0606', 'PH', 0),
        ('Capiz', '0619', 'PH', 0),
        ('Iloilo', '0630', 'PH', 0),
        ('Negros Occidental', '0645', 'PH', 0),
        ('Guimaras', '0679', 'PH', 0),
        ('Bohol', '0712', 'PH', 0),
        ('Cebu', '0722', 'PH', 0),
        ('Negros Oriental', '0746', 'PH', 0),
        ('Siquijor', '0761', 'PH', 0),
        ('Eastern Samar', '0826', 'PH', 0),
        ('Leyte', '0837', 'PH', 0),
        ('Northern Samar', '0848', 'PH', 0),
        ('Samar (Western Samar)', '0860', 'PH', 0),
        ('Southern Leyte', '0864', 'PH', 0),
        ('Biliran', '0878', 'PH', 0),
        ('Zamboanga Del Norte', '0972', 'PH', 0),
        ('Zamboanga Del Sur', '0973', 'PH', 0),
        ('Zamboanga Sibugay', '0983', 'PH', 0),
        ('Bukidnon', '1013', 'PH', 0),
        ('Camiguin', '1018', 'PH', 0),
        ('Lanao Del Norte', '1035', 'PH', 0),
        ('Misamis Occidental', '1042', 'PH', 0),
        ('Misamis Oriental', '1043', 'PH', 0),
        ('Davao Del Norte', '1123', 'PH', 0),
        ('Davao Del Sur', '1124', 'PH', 0),
        ('Davao Oriental', '1125', 'PH', 0),
        ('Compostela Valley', '1182', 'PH', 0),
        ('Davao Occidental', '1186', 'PH', 0),
        ('Cotabato (North Cotabato)', '1247', 'PH', 0),
        ('South Cotabato', '1263', 'PH', 0),
        ('Sultan Kudarat', '1265', 'PH', 0),
        ('Sarangani', '1280', 'PH', 0),
        ('Cotabato City', '1298', 'PH', 0),
        ('NCR - 1st District', '1339', 'PH', 0),
        ('NCR - 2nd District', '1374', 'PH', 0),
        ('NCR - 3rd District', '1375', 'PH', 0),
        ('NCR - 4th District', '1376', 'PH', 0),
        ('Abra', '1401', 'PH', 0),
        ('Benguet', '1411', 'PH', 0),
        ('Ifugao', '1427', 'PH', 0),
        ('Kalinga', '1432', 'PH', 0),
        ('Mountain Province', '1444', 'PH', 0),
        ('Apayao', '1481', 'PH', 0),
        ('Basilan', '1507', 'PH', 0),
        ('Lanao Del Sur', '1536', 'PH', 0),
        ('Maguindanao', '1538', 'PH', 0),
        ('Sulu', '1566', 'PH', 0),
        ('Tawi-tawi', '1570', 'PH', 0),
        ('Agusan Del Norte', '1602', 'PH', 0),
        ('Agusan Del Sur', '1603', 'PH', 0),
        ('Surigao Del Norte', '1667', 'PH', 0),
        ('Surigao Del Sur', '1668', 'PH', 0),
        ('Dinagat Islands', '1685', 'PH', 0);
    ";

    //Defining list
    define("PROV_LIST", $prov_list);


    //Defining table fields
    define("PROV_DATA_FIELDS", "(prov_name, prov_code, country_code, status)");
