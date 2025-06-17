import 'dart:ui';

import 'package:nb_utils/nb_utils.dart';
import 'package:url_launcher/url_launcher.dart';

const purchaseURL = "https://codecanyon.net/item/prokit-flutter-app-ui-design-templete-kit/25787190";

Future<void> launchUrl(String url, {bool forceWebView = false}) async {
  await launch(url, forceWebView: forceWebView, enableJavaScript: true, statusBarBrightness: Brightness.light).catchError((e) {
    toast('Invalid URL: $url');
  });
}
