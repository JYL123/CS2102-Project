#!/bin/sh
echo $PATH | egrep "/Applications/mappstack-7.1.13-0/common" > /dev/null
if [ $? -ne 0 ] ; then
PATH="/Applications/mappstack-7.1.13-0/frameworks/laravel/app/Console:/Applications/mappstack-7.1.13-0/frameworks/cakephp/bin:/Applications/mappstack-7.1.13-0/frameworks/codeigniter/bin:/Applications/mappstack-7.1.13-0/frameworks/symfony/bin:/Applications/mappstack-7.1.13-0/frameworks/zendframework/app/Console:/Applications/mappstack-7.1.13-0/git/bin:/Applications/mappstack-7.1.13-0/varnish/bin:/Applications/mappstack-7.1.13-0/sqlite/bin:/Applications/mappstack-7.1.13-0/php/bin:/Applications/mappstack-7.1.13-0/postgresql/bin:/Applications/mappstack-7.1.13-0/apache2/bin:/Applications/mappstack-7.1.13-0/common/bin:$PATH"
export PATH
fi
echo $DYLD_FALLBACK_LIBRARY_PATH | egrep "/Applications/mappstack-7.1.13-0/common" > /dev/null
if [ $? -ne 0 ] ; then
DYLD_FALLBACK_LIBRARY_PATH="/Applications/mappstack-7.1.13-0/git/lib:/Applications/mappstack-7.1.13-0/varnish/lib:/Applications/mappstack-7.1.13-0/varnish/lib/varnish:/Applications/mappstack-7.1.13-0/varnish/lib/varnish/vmods:/Applications/mappstack-7.1.13-0/sqlite/lib:/Applications/mappstack-7.1.13-0/postgresql/lib:/Applications/mappstack-7.1.13-0/apache2/lib:/Applications/mappstack-7.1.13-0/common/lib:/usr/local/lib:/lib:/usr/lib:$DYLD_FALLBACK_LIBRARY_PATH"
export DYLD_FALLBACK_LIBRARY_PATH
fi

TERMINFO=/Applications/mappstack-7.1.13-0/common/share/terminfo
export TERMINFO
##### GIT ENV #####
GIT_EXEC_PATH=/Applications/mappstack-7.1.13-0/git/libexec/git-core/
export GIT_EXEC_PATH
GIT_TEMPLATE_DIR=/Applications/mappstack-7.1.13-0/git/share/git-core/templates
export GIT_TEMPLATE_DIR
GIT_SSL_CAINFO=/Applications/mappstack-7.1.13-0/common/openssl/certs/curl-ca-bundle.crt
export GIT_SSL_CAINFO

##### VARNISH ENV #####
		
      ##### SQLITE ENV #####
			
##### GHOSTSCRIPT ENV #####
GS_LIB="/Applications/mappstack-7.1.13-0/common/share/ghostscript/fonts"
export GS_LIB
##### IMAGEMAGICK ENV #####
MAGICK_HOME="/Applications/mappstack-7.1.13-0/common"
export MAGICK_HOME

MAGICK_CONFIGURE_PATH="/Applications/mappstack-7.1.13-0/common/lib/ImageMagick-6.9.8/config-Q16:/Applications/mappstack-7.1.13-0/common/"
export MAGICK_CONFIGURE_PATH

MAGICK_CODER_MODULE_PATH="/Applications/mappstack-7.1.13-0/common/lib/ImageMagick-6.9.8/modules-Q16/coders"
export MAGICK_CODER_MODULE_PATH

##### FONTCONFIG ENV #####
FONTCONFIG_PATH="/Applications/mappstack-7.1.13-0/common/etc/fonts"
export FONTCONFIG_PATH
SASL_CONF_PATH=/Applications/mappstack-7.1.13-0/common/etc
export SASL_CONF_PATH
SASL_PATH=/Applications/mappstack-7.1.13-0/common/lib/sasl2 
export SASL_PATH
LDAPCONF=/Applications/mappstack-7.1.13-0/common/etc/openldap/ldap.conf
export LDAPCONF
##### PHP ENV #####
PHP_PATH=/Applications/mappstack-7.1.13-0/php/bin/php
COMPOSER_HOME=/Applications/mappstack-7.1.13-0/php/composer
export PHP_PATH
export COMPOSER_HOME
##### POSTGRES ENV #####

        ##### APACHE ENV #####

##### FREETDS ENV #####
FREETDSCONF=/Applications/mappstack-7.1.13-0/common/etc/freetds.conf
export FREETDSCONF
FREETDSLOCALES=/Applications/mappstack-7.1.13-0/common/etc/locales.conf
export FREETDSLOCALES
##### CURL ENV #####
CURL_CA_BUNDLE=/Applications/mappstack-7.1.13-0/common/openssl/certs/curl-ca-bundle.crt
export CURL_CA_BUNDLE
##### SSL ENV #####
SSL_CERT_FILE=/Applications/mappstack-7.1.13-0/common/openssl/certs/curl-ca-bundle.crt
export SSL_CERT_FILE
OPENSSL_CONF=/Applications/mappstack-7.1.13-0/common/openssl/openssl.cnf
export OPENSSL_CONF
OPENSSL_ENGINES=/Applications/mappstack-7.1.13-0/common/lib/engines
export OPENSSL_ENGINES


. /Applications/mappstack-7.1.13-0/scripts/build-setenv.sh
