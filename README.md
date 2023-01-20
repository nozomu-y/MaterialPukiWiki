<p align="center">
    <h1 align="center">MaterialPukiWiki</h1>
    <p align="center">A modern skin for PukiWiki.</p>
</p>

MaterialPukiWiki は、[MDBootstrap](https://mdbootstrap.com/)を用いて作成された[PukiWiki](https://pukiwiki.osdn.jp/)の自作スキンです。

## Demo 
https://nozomu.dev/MaterialPukiWiki/

<kbd><img src="https://raw.githubusercontent.com/nozomu-y/MaterialPukiWiki/image/image/sample_lg.png"></img></kbd>

<kbd><img style="width:300px;max-width:100%;" src="https://raw.githubusercontent.com/nozomu-y/MaterialPukiWiki/image/image/sample_sm.png"></kbd>

## Installation

### Download skin

```sh
$ cd /path/to/PukiWiki/skin
$ git clone https://github.com/nozomu-y/MaterialPukiWiki.git
```

### Apply MaterialPukiWiki

Change the line 17 of `/path/to/PukiWiki/default.ini.php`.

```diff
- define('SKIN_FILE', DATA_HOME . SKIN_DIR . 'pukiwiki.skin.php');
+ define('SKIN_FILE', DATA_HOME . SKIN_DIR . 'MaterialPukiWiki/pukiwiki.skin.php');
```
