# GetMember
Member Get Member - Magento 1


```
$ cd /path/to/module
$ git clone https://github.com/MageGoat/GetMember.git
```
```
$ cd path/to/magento
$ modman link --copy GetMember <path to module>
```

Alterando o modulo no **/path/to/module** executar o comando no **/path/to/magento** para atualizar.
```
$ modman update GetMember --force
```

link simbolito com problemas * analisando...


-------------------
reset module  MySQL
```
DROP TABLE `getmember_member`;
DROP TABLE `getmember_point`;
DELETE FROM `core_resource` WHERE `core_resource`.`code` = 'getmember_setup';
```
