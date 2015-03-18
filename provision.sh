#Basic install of an apache PHP server
echo INSTALLING PACKAGES
sudo apt-get update
sudo apt-get --yes install git
sudo apt-get --yes install autoconf bison build-essential libssl-dev libyaml-dev libreadline6 libreadline6-dev zlib1g zlib1g-dev
sudo apt-get --yes install apache2 
sudo apt-get --yes install php5 libapache2-mod-php5 php5-mcrypt

#sudo apt-get --yes install postgresql postgresql-contrib libpq-dev 
#sudo apt-get --yes install sqlite3 libsqlite3-dev

# enable mod rewrite
sudo a2enmod rewrite
#TODO need to configure AllowOverride All

sudo service apache2 restart

# link the dir
sudo ln -s  /vagrant /var/www/vagrant

#sudo mkdir -p /vagrant/contentcache
#sudo chmod ugo+w /vagrant/contentcache

