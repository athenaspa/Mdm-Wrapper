
# Wrapper Class to interact with MDM api.

Put this in your composer.json


```javascript
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/athenaspa/Mdm-Wrapper.git"
    },
    {
      "type": "vcs",
      "url": "git@bitbucket.org:athena_group/swaggerclient-php.git"
    }
  ],
  "require": {
    "athena_group/mdm-wrapper": "dev-master",
    "athena_group/swaggerclient-php": "dev-master",
    "mnsami/composer-custom-directory-installer": "1.1.*"
  },
  "extra": {
    "installer-paths": {
      "./vendor/athena/{$name}": ["athena_group/mdm-wrapper","athena_group/swaggerclient-php"]
    }
  }  
}
```

Populate your **.env** file with your credential

```javascript
HOST=
CLIENT_ID=
CLIENT_SECRET=
EMAIL=
PASSWORD=
```
