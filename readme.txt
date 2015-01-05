=== Woocommerce Lembrete Boleto ===
Contributors: agenciamagma, Carlos Cardoso Dias
Donate link: http://www.agenciamagma.com.br
Tags: woocommerce boleto, woocommerce lembrete, woocommerce, boleto, lembrete, email
Requires at least: 4.0.1
Tested up to: 4.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send email to on-hold boleto orders with few days left to pay.

== Description ==

This plugin sends an e-mail few days before boleto orders expires to remind the user about his non paid purchase with a link to the boleto.

= Descrição em Português =

Este plugin envia um e-mail para os usuários que fizeram um pedido selecionando `boleto` como tipo de pagamento que ainda estejam no status `Aguardando`. O e-mail é enviado por padrão todos os dias à partir de 1 dia restante para o pagamento às 00:00.

== Installation ==

1. Upload `woocommerce-lembrete-boleto.php` to the `/wp-content/plugins/woocommerce-lembrete-boleto/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Do I have to install Woocommerce and Woocommerce Boleto to use this plugin? =

Yes. The plugin was been tested under woocommerce 2.2.8 and woocommer-boleto 1.4.1, so that might be enough.

== Screenshots ==

1. This screenshot shows an example of the e-mail delivered to the user.

== Changelog ==

= 1.0.3 =
* Fixed bug in the plugin activation.
* Compatibility test with WordPress 4.1.

= 1.0.2 =
* E-mail with WooCommerce template.

= 1.0 =
* First version.

== Upgrade Notice ==

= 1.0.3 =
This version fixes the `Headers already sent` bug in the plugin activation, it's extremely recomended. This update is strongly recommended for the correct operation of the plugin.

= 1.0.2 =
This version uses a cool WooCommerce default template for e-mails.

= 1.0 =
First version.
