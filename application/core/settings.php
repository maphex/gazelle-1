<?php
namespace gazelle\core;

use gazelle\errors\ConfigurationError;

class Settings {

    protected $defaults = [
        'main' => [
            'site_name' => '',
            'nonssl_site_url' => '',
            'ssl_site_url' => '',
            'site_ip' => '',
            'announce_url' => null,
            'nonssl_static_server' => 'static/',
            'ssl_static_server' => 'static/',
            'site_url' => null,
            'static_server' => null,
            'additional_domains' => 'somelinkedsite.com,anotherlinkedsite.com',
            'internal_urls_regex' => null
        ],
        'keys' => [
            'enckey' => '',
            'schedule_key' => '',
            'rss_hash' => ''
        ],
        'database' => [
            'host' => 'localhost',
            'username' => '',
            'password' => '',
            'db' => 'gazelle',
            'port' => 3306,
            'socket' => '/var/run/mysqld/mysqld.sock'
        ],
        'memcached' => [
            'host' => 'unix:///var/run/memcached.sock',
            'port' => 0
        ],
        'sphinx' => [
            'host' => 'localhost',
            'port' => 9312,
            'max_matches' => 1000,
            'matches_start' => 100,
            'matches_step' => 50,
            'index' => 'torrents'
        ],
        'tracker' => [
            'host' => 'localhost',
            'port' => 2710,
            'secret' => ''
        ],
        'site' => [
            'debug_mode' => false,
            'open_registration' => true,
            'user_limit' => 5000,
            'starting_invites' => 0,
            'block_tor' => false,
            'block_opera_mini' => false,
            'donor_invites' => 2,
            'user_edit_post_time' => 900,
            'user_flood_post_time' => 10,
            'thread_catalogue' => 500,
            'chat_url' => '',
            'help_url' => '',
            'advert_html' => '',
            'anonymizer_url' => 'http://anonym.to/?'
        ],
        'users' => [
            'classes' => 'APPRENTICE=2,PERV=3,GOOD_PERV=4,DONOR=20,SEXTREME_PERV=5,SMUT_PEDDLER=6,ADMIN=1,SYSOP=15',
            'level_staff' => 500,
            'level_admin' => 600,
        ],
        'pagination' => [
            'torrent_comments' => 10,
            'posts' => 25,
            'topics' => 50,
            'torrents' => 50,
            'requests' => 25,
            'messages' => 25,
            'log_entries' => 50
        ],
        'ircbot' => [
            'nick' => '',
            'server' => '',
            'port' => 6667,
            'chan' => '',
            'announce_chan' => '',
            'staff_chan' => '',
            'disabled_chan' => '',
            'help_chan' => '',
            'debug_chan' => '',
            'report_chan' => '',
            'admin_chan' => '',
            'lab_chan' => '',
            'status_chan' => '',
            'nickserv_pass' => '',
            'listen_port' => 51010,
            'listen_address' => 'localhost'
        ],
        'forums' => [
            'sig_max_width' => 800,
            'sig_max_height' => 300,
            'title_maxword_length' => 42,
            'announcement_forum_id' => 5,
            'staff_forum_id' => 0,
            'trash_forum_id' => 18,
            'exclude_forums' => '9',
            'forums_reveal_voters' => '15,21',
            'forums_double_post' => ''
        ],
        'torrents' => [
            'auto_freeleech_size' => 32212254720, # 30 GB
            'bonus_torrents_cap' => 300,
            'torrent_sig_max_height' => 800,
            'enhanced_vote_power' => 2,
            'exclude_dupes_after_days' => 183,
            'exclude_dupes_seeds' => 5,
            'torrent_edit_time' => 1209600, # 3600 * 24 * 14
            'uploader_request_time' => 86400,
            'request_approval_size' => 107374182400, # 100 GB,
            'request_filler_share' => 0.5
        ],
    ];

    protected $legacy_constant_names = [
        'main' => [
            'site_name' => 'SITE_NAME',
            'nonssl_site_url' => 'NONSSL_SITE_URL',
            'ssl_site_url' => 'SSL_SITE_URL',
            'site_ip' => 'SITE_IP',
            'announce_url' => 'ANNOUNCE_URL',
            'nonssl_static_server' => 'NONSSL_STATIC_SERVER',
            'ssl_static_server' => 'SSL_STATIC_SERVER',
            'site_url' => 'SITE_URL',
            'static_server' => 'STATIC_SERVER',
            'internal_urls_regex' => 'INTERNAL_URLS_REGEX'
        ],
        'keys' => [
            'enckey' => 'ENCKEY',
            'schedule_key' => 'SCHEDULE_KEY',
            'rss_hash' => 'RSS_HASH'
        ],
        'database' => [
            'host' => 'SQLHOST',
            'username' => 'SQLLOGIN',
            'password' => 'SQLPASS',
            'db' => 'SQLDB',
            'port' => 'SQLPORT',
            'socket' => 'SQLSOCK'
        ],
        'memcached' => [
            'host' => 'MEMCACHED_HOST',
            'port' => 'MEMCACHED_PORT'
        ],
        'sphinx' => [
            'host' => 'SPHINX_HOST',
            'port' => 'SPHINX_PORT',
            'max_matches' => 'SPHINX_MAX_MATCHES',
            'matches_start' => 'SPHINX_MATCHES_START',
            'matches_step' => 'SPHINX_MATCHES_STEP',
            'index' => 'SPHINX_INDEX'
        ],
        'tracker' => [
            'host' => 'TRACKER_HOST',
            'port' => 'TRACKER_PORT',
            'secret' => 'TRACKER_SECRET'
        ],
        'site' => [
            'debug_mode' => 'DEBUG_MODE',
            'open_registration' => 'OPEN_REGISTRATION',
            'user_limit' => 'USER_LIMIT',
            'starting_invites' => 'STARTING_INVITES',
            'block_tor' => 'BLOCK_TOR',
            'block_opera_mini' => 'BLOCK_OPERA_MINI',
            'donor_invites' => 'DONOR_INVITES',
            'user_edit_post_time' => 'USER_EDIT_POST_TIME',
            'user_flood_post_time' => 'USER_FLOOD_POST_TIME',
            'thread_catalogue' => 'THREAD_CATALOGUE',
            'chat_url' => 'CHAT_URL',
            'help_url' => 'HELP_URL',
            'advert_html' => 'ADVERT_HTML',
            'anonymizer_url' => 'ANONYMIZER_URL'
        ],
        'users' => [
            'level_staff' => 'LEVEL_STAFF',
            'level_admin' => 'LEVEL_ADMIN'
        ],
        'pagination' => [
            'torrent_comments' => 'TORRENT_COMMENTS_PER_PAGE',
            'posts' => 'POSTS_PER_PAGE',
            'topics' => 'TOPICS_PER_PAGE',
            'torrents' => 'TORRENTS_PER_PAGE',
            'requests' => 'REQUESTS_PER_PAGE',
            'messages' => 'MESSAGES_PER_PAGE',
            'log_entries' => 'LOG_ENTRIES_PER_PAGE'
        ],
        'ircbot' => [
            'nick' => 'BOT_NICK',
            'server' => 'BOT_SERVER',
            'port' => 'BOT_PORT',
            'chan' => 'BOT_CHAN',
            'announce_chan' => 'BOT_ANNOUNCE_CHAN',
            'staff_chan' => 'BOT_STAFF_CHAN',
            'disabled_chan' => 'BOT_DISABLED_CHAN',
            'help_chan' => 'BOT_HELP_CHAN',
            'debug_chan' => 'BOT_DEBUG_CHAN',
            'report_chan' => 'BOT_REPORT_CHAN',
            'admin_chan' => 'ADMIN_CHAN',
            'lab_chan' => 'LAB_CHAN',
            'status_chan' => 'STATUS_CHAN',
            'nickserv_pass' => 'BOT_NICKSERV_PASS',
            'listen_port' => 'SOCKET_LISTEN_PORT',
            'listen_address' => 'SOCKET_LISTEN_ADDRESS',
        ],
        'forums' => [
            'sig_max_width' => 'SIG_MAX_WIDTH',
            'sig_max_height' => 'SIG_MAX_HEIGHT',
            'title_maxword_length' => 'TITLE_MAXWORD_LENGTH',
            'announcement_forum_id' => 'ANNOUNCEMENT_FORUM_ID',
            'staff_forum_id' => 'STAFF_FORUM_ID',
            'trash_forum_id' => 'TRASH_FORUM_ID'
        ],
        'torrents' => [
            'auto_freeleech_size' => 'AUTO_FREELEECH_SIZE',
            'bonus_torrents_cap' => 'BONUS_TORRENTS_CAP',
            'torrent_sig_max_height' => 'TORRENT_SIG_MAX_HEIGHT',
            'enhanced_vote_power' => 'ENHANCED_VOTE_POWER',
            'exclude_dupes_after_days' => 'EXCLUDE_DUPES_AFTER_DAYS',
            'exclude_dupes_seeds' => 'EXCLUDE_DUPES_SEEDS',
            'torrent_edit_time' => 'TORRENT_EDIT_TIME',
            'uploader_request_time' => 'UPLOADER_REQUEST_TIME',
            'request_approval_size' => 'REQUEST_APPROVAL_SIZE',
            'request_filler_share' => 'REQUEST_FILLER_SHARE'
        ],

    ];

    protected $master;
    protected $settings;

    public function __construct(Master $master, $settings_file) {
        $this->master = $master;
        $this->settings = $this->defaults;
        $this->read_settings_file($settings_file);
        $this->fill_settings();
    }

    protected function read_settings_file($filename) {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new ConfigurationError("Unable to read settings file: {$filename}");
        }
        $file_settings = parse_ini_file($filename, true);
        foreach ($file_settings as $section_name => $section) {
            foreach ($section as $setting => $value) {
                $this->settings[$section_name][$setting] = $value;
            }
        }
    }

    protected function print_settings_template() {
        # This function isn't currently used anywhere, but its purpose is hopefully obvious.
        print("; application/settings.ini.template: copy this to applications/settings.ini ,\n"
             ."; and enter the appropriate values.\n"
             ."; Settings which don't have to be changed from the default can be left commented\n"
             ."; out or deleted entirely.\n");

        foreach ($this->settings as $section_name => $section) {
            print("\n[{$section_name}]\n");
            foreach ($section as $setting => $value) {
                if ($value===true) {
                    $valstr = 'On';
                } elseif ($value===false) {
                    $valstr = 'Off';
                } elseif (is_int($value) || is_float($value)) {
                    $valstr = strval($value);
                } else {
                    $valstr = "'" . strval($value) . "'";
                }
                print(";{$setting} = {$valstr}\n");
                if (array_key_exists($section_name, $this->legacy_constant_names)
                 && array_key_exists($setting, $this->legacy_constant_names[$section_name])) {
                    print("; (was: {$this->legacy_constant_names[$section_name][$setting]})\n");
                }
            }
        }
    }

    public function __get($section_name) {
        # this allows settings to be read as ->section_name->setting_name
        if (is_array($this->settings[$section_name])) {
            $section_object = (object) $this->settings[$section_name];
            return $section_object;
        }
    }

    protected function fill_settings() {
        if (is_null($this->settings['main']['announce_url'])) {
            $this->settings['main']['announce_url'] = 'http://' . $this->settings['main']['nonssl_site_url'] . ':' . $this->settings['tracker']['port'];
        }

        $is_ssl = (array_key_exists('SERVER_PORT', $this->master->server) && intval($this->master->server['SERVER_PORT']) != 80);
        if (is_null($this->settings['main']['site_url'])) {
            $this->settings['main']['site_url'] = ($is_ssl) ? $this->settings['main']['ssl_site_url'] : $this->settings['main']['nonssl_site_url'];
        }
        if (is_null($this->settings['main']['static_server'])) {
            $this->settings['main']['static_server'] = ($is_ssl) ? $this->settings['main']['ssl_static_server'] : $this->settings['main']['nonssl_static_server'];
        }

        if (is_null($this->settings['main']['internal_urls_regex'])) {
            $internal_urls_regex = '@' . $this->settings['main']['nonssl_site_url'] . '/';
            foreach (explode(',', $this->main->additional_domains) as $domain) {
                # FIXME: $domain should really have '.' replaced by '\.', but for now we're only mimicking existing behaviour
                $internal_urls_regex .= '|' . $domain . '/';
            }
        }
    }

    public function set_legacy_constants() {
        global $ForumsRevealVoters, $ForumsDoublePost, $CollageCats, $CollageIcons, $ArticleCats, $ArticleSubCats, $BadgeTypes, $AutoAwardTypes, $ShopActions, $Video_FileTypes, $Image_FileTypes, $Zip_FileTypes, $ExcludeForums, $DonateLevels, $ExcludeBytesDupeCheck, $CaptchaFonts, $CaptchaBGs, $SpecialChars;

        foreach ($this->legacy_constant_names as $section_name => $section) {
            foreach ($section as $key => $constant_name) {
                $value = $this->settings[$section_name][$key];
                define($constant_name, $value);
            }
        }

        $userclasses = $this->settings['users']['classes'];
        foreach (explode(',', $userclasses) as $userclass) {
            list($constant_name, $value) = explode('=', $userclass);
            define($constant_name, $value);
        }

        $ExcludeForums = explode(',', $this->settings['forums']['exclude_forums']);
        $ForumsRevealVoters = explode(',', $this->settings['forums']['forums_reveal_voters']);
        $ForumsDoublePost = explode(',', $this->settings['forums']['forums_double_post']);


        # The rest we just leave plain & hardcoded for now
        # To either be changed into a setting in the future, or solved in a different way altogether

        define('BTC_ADDRESS_REGEX', "/^[13]{1}[a-km-zA-HJ-NP-Z1-9]{26,34}$/");
        //kind of random var but testing/changing this with live data is required 
        //- this is the % under which results are aggregated by the clients graph, 
        //if its too low (num clients depending) it breaks the google url api 
        define('CLIENT_GRAPH_OTHER_PERCENT', 0.5);

        define('MAX_FILE_SIZE_BYTES', 2097152); // the max filesize (enforced in client side and server side using this value)


        $CollageCats = array(0=>'Personal', 1=>'Theme', 2=>'Porn Star', 3=>'Studio', 4=>'Staff picks');
        $CollageIcons = array(0=>'col_personal.png', 1=>'col_themed.png', 2=>'col_pornstar.png', 3=>'col_studio.png', 4=>'col_staffpicks.png');

        $ArticleCats = array(0=>'Rules', 1=>'Help', 2=>'Hidden');
        $ArticleSubCats = array(0=>'Intro', 1=>'Other', 2=>'Rules', 3=>'Torrents', 4=>'IRC', 5=>'Uploading', 6=>'Site', 7=>'Bitcoin Guides', 8=>'Staff');

        // badge types
        $BadgeTypes = array ('Single', 'Multiple', 'Shop', 'Unique','Donor');
        $AutoAwardTypes  = array ('NumPosts', 'NumComments', 'NumUploaded', 'NumNewTags', 'NumTags', 'NumTagVotes',
                          'RequestsFilled', 'UploadedTB', 'DownloadedTB', 'MaxSnatches', 'NumBounties', 'AccountAge');

        $ShopActions = array('gb','givegb','givecredits','slot','title','badge','pfl','ufl');

        // for counting filetypes
        $Video_FileTypes = array('3gp','aaf','asf','avi','divx','f4v','flv','hdmov','m2v','m4v','mpeg','m1v','mkv','mov','mp4','mpg','ogg','ogv','qt','rm','rmvb','swf','wmv','vob');
        $Image_FileTypes = array('bmp','gif','jpeg','jpg','png');
        $Zip_FileTypes = array('7','7z','gz','gzip','rar','z','zip');

         
        $DonateLevels = array ( 1 => 1.0, 10 => 1.5, 50 => 2.0, 100 => 5 );

        // key should be bytesize to exclude from dupe via bytesize check, value is reason displayed to user
        $ExcludeBytesDupeCheck = array ( 734015488=>'a standard cd size', 1065353216=>'a standard vob size',  1073739776 => 'a standard vob size' );

        //
        //Captcha fonts should be located in /classes/fonts
        $CaptchaFonts=array('ARIBLK.TTF','IMPACT.TTF','TREBUC.TTF','TREBUCBD.TTF','TREBUCBI.TTF','TREBUCIT.TTF','VERDANA.TTF','VERDANAB.TTF','VERDANAI.TTF','VERDANAZ.TTF');
        //Captcha images should be located in /captcha
        $CaptchaBGs=array('captcha1.png','captcha2.png','captcha3.png','captcha4.png','captcha5.png','captcha6.png','captcha7.png','captcha8.png','captcha9.png');

        // Special characters, and what they should be converted to
        // Used for torrent searching
        $SpecialChars = array(
                '&' => 'and'
        );
    }
}
