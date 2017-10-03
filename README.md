# AddressBundle

Configuration
-------------

AppKernel
---------

```php
# app/AppKernel.php

<?php

public function registerBundles()
{
    $bundles = array(
        // ...
        new KRG\AddressBundle\KRGAddressBundle(),
        // ...
    );
}
```


Configuration
-------------

```yaml
# app/config/config.yml

krg_address:
    google_maps:
        api_key: '%google_maps_api_key%'

doctrine:
    orm:
        resolve_target_entities:
            KRG\AddressBundle\Entity\AddressInterface: AppBundle\Entity\Address
            KRG\AddressBundle\Entity\CountryInterface: AppBundle\Entity\Country
```

Entity
------

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="address")
 */
class Address extends \KRG\AddressBundle\Entity\Address
{
}
```

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="country")
 */
class Country extends \KRG\AddressBundle\Entity\Country
{
}
```
