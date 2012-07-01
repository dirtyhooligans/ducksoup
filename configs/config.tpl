[core]
config                = local
lang                  = en;
debug                 = true
site_name             = "[local] ducksoup"
copyright             = "dirtyhooligans.com"
library.path          = library/core
applications.root     = applications
default.app           = ducksoup
default.controller    = default
default.method        = main

downloads.base_dir    = ../download
downloads.base_url    = http://local.orpheus.com/download

email.template_path   = private/templates/emails
email.img_path        = images/emails
email.admin           = "steve@dirtyhooligans.com"
email.noreply         = "noreply@dirtyhooligans.com"

auth.session_timeout  = 1800

db.main.adapter = Mysqli 
db.main.user = dirtyhsc_duck1
db.main.pass = "dirty"
db.main.host = "localhost"
db.main.port = 3306
db.main.name = dirtyhsc_duck

db.ducksoup.adapter = Mysqli 
db.ducksoup.user = dirtyhsc_ducksoup
db.ducksoup.pass = "dirty"
db.ducksoup.host = localhost
db.ducksoup.port = 3306
db.ducksoup.name = dirtyhsc_ducksoup

api.lastfm.key = a875a62f3ec794bb698035bac116feb0
api.lastfm.secret = d8d84efde889f493180406e2bab41a50

api.yelp.consumer_key    = UXtuSkh5sFVDmCtWt67Hgw
api.yelp.consumer_secret = eje5LolWzsI2inBqZI7UNhSv26U
api.yelp.token           = _BLZdhC2MOdZZeX5ZPBjMYWhrM3cJEsj
api.yelp.token_secret    = r8Pw1dgm9WT3_nhAbzn6Fu56OWc
api.yelp.url             = http://api.yelp.com/v2
