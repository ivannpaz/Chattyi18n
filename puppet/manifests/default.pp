exec { 'apt-get update':
  command => 'apt-get update',
  path    => '/usr/bin/',
  timeout => 60,
  tries   => 3,
}

class { 'apt':
  always_apt_update => true,
}

package { ['python-software-properties']:
  ensure  => 'installed',
  require => Exec['apt-get update'],
}

file { '/home/vagrant/.bash_aliases':
  ensure => 'present',
  source => 'puppet:///modules/puphpet/dot/.bash_aliases',
}

package { ['build-essential', 'vim', 'curl']:
  ensure  => 'installed',
  require => Exec['apt-get update'],
}

class { 'apache': }

apache::dotconf { 'custom':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { 'localhost':
  server_name   => 'localhost',
  docroot       => '/vagrant/site/public/',
  port          => '80',
  env_variables => [],
  priority      => '1',
}

apt::ppa { 'ppa:ondrej/php5':
  before  => Class['php'],
}

class { 'php':
  service => 'apache',
  require => Package['apache'],
}

php::module { 'php5-mysql': }
php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }

class { 'php::devel':
  require => Class['php'],
}

class { 'php::pear':
  require => Class['php'],
}

class { 'php::composer': }

php::ini { 'php':
  value   => ['date.timezone = "Europe/Madrid"'],
  target  => 'php.ini',
  service => 'apache',
}
php::ini { 'custom':
  value   => ['display_errors = On', 'error_reporting = -1'],
  target  => 'custom.ini',
  service => 'apache',
}

class { 'mysql':
  root_password => '123',
}

mysql::grant { 'development':
  mysql_privileges     => 'ALL',
  mysql_db             => 'appdatabase',
  mysql_user           => 'developer',
  mysql_password       => '123',
  mysql_host           => 'localhost',
  mysql_grant_filepath => '/home/vagrant/puppet-mysql',
}

class { 'redis':
  conf_port => '6379',
  conf_bind => '127.0.0.1',
}

include xdebug

xdebug::config { 'default':
  default_enable => 1,
  remote_enable => 1,
  remote_handler => 'dbgp',
  remote_host => '192.168.56.1',
  remote_port => '9000',
  remote_connect_back => 0,
  remote_autostart => 0,
  remote_log => '/tmp/xdebug.log',
}
