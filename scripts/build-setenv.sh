#!/bin/sh
LDFLAGS="-L/Applications/mappstack-7.1.13-0/common/lib $LDFLAGS"
export LDFLAGS
CFLAGS="-I/Applications/mappstack-7.1.13-0/common/include/ImageMagick -I/Applications/mappstack-7.1.13-0/common/include $CFLAGS"
export CFLAGS
CXXFLAGS="-I/Applications/mappstack-7.1.13-0/common/include $CXXFLAGS"
export CXXFLAGS
		    
PKG_CONFIG_PATH="/Applications/mappstack-7.1.13-0/common/lib/pkgconfig"
export PKG_CONFIG_PATH
