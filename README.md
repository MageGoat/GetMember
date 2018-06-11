# GetMember
Member Get Member - Magento 1


```
$ cd /path/to/module
$ git clone https://github.com/Goat/GetMember.git
```


```
$ cd path/to/magento
$ modman link GetMember <path to module>
```


magento aceita link simbolico, alterarando no app/etc/config.xml.
```
 <dev>
    <template>
        <allow_symlink>1</allow_symlink>
    </template>
</dev>
```


de link simbolico para copia.
```
modman deploy --copy GetMember
```

-------------------
reset module  MySQL
```
DROP TABLE `getmember_member`;
DROP TABLE `getmember_point`;
DELETE FROM `core_resource` WHERE `core_resource`.`code` = 'getmember_setup';
```

![Mage Goat](https://github.com/MageGoat/GetMember/blob/master/goat.gif)