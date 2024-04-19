<?php
// rename to config.php

// The database details
define('DB_NAME', 'db_name');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'host');

// The details of your SMTP service,
define('SMTP_HOST', 'smtp.ethereal.email');
define('SMTP_USERNAME', 'mail@email.com');
define('SMTP_PASSWORD', 'password');

// Which SMTP port and encryption type to use.
define('SMTP_ENCRYPTION', 'tls');
define('SMTP_PORT', 587);

// The name and address which should be used for the sender details.
// The name can be anything you want, the address should be something in your own domain. It does not need to exist as a mailbox.
define('SMTP_FROM', 'mail@email.com');
define('SMTP_FROM_NAME', 'name');

// The name and address to which the contact message should be sent.
// These details should NOT be the same as the sender details.
define('SMTP_TO', 'mail@email.com');
define('SMTP_TO_NAME', 'name');

// Whether or not allow sending message.
define('SEND_MESSAGE', true);