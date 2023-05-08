Secret Server feladathoz (https://github.com/ngabesz-wse/secret-server-task) tartozó megoldásom.

https://secretserversv.000webhostapp.com/ urlen elérhető.

A következő utasításokkal teszteltem:

Új titok létrehozása (json) :
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -H "Accept: application/json" -d "secret=MySecret&expireAfterViews=10&expireAfter=60" https://secretserversv.000webhostapp.com/secret

Már meglévő titok lekérdezése (json) :
curl -X 'GET' -H 'accept: application/json' https://secretserversv.000webhostapp.com/secret/64588efbd8a0d

Új titok létrehozása (xml) :
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -H "Accept: application/xml" -d "secret=MySecret&expireAfterViews=10&expireAfter=60" https://secretserversv.000webhostapp.com/secret

Már meglévő titok lekérdezése (xml) :
curl -X 'GET' -H 'accept: application/xml' https://secretserversv.000webhostapp.com/secret/64588efbd8a0d
