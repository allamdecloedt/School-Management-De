import 'package:flutter/material.dart';
import 'package:nb_utils/nb_utils.dart';
import 'package:wallet_flutter/screen/WAWalkThroughScreen.dart';
import 'package:wallet_flutter/utils/WAColors.dart';

class WASplashScreen extends StatefulWidget {
  static String tag = '/WASplashScreen';

  @override
  WASplashScreenState createState() => WASplashScreenState();
}

class WASplashScreenState extends State<WASplashScreen> {
  @override
  void initState() {
    super.initState();
    init();
  }

  Future<void> init() async {
    setStatusBarColor(WAPrimaryColor, statusBarIconBrightness: Brightness.light);
    await Future.delayed(Duration(seconds: 3));
    if (mounted) finish(context);
    WAWalkThroughScreen().launch(context, isNewTask: true);
  }

  @override
  void dispose() {
    setStatusBarColor(Colors.white, statusBarIconBrightness: Brightness.dark);
    super.dispose();
  }

  @override
  void setState(fn) {
    if (mounted) super.setState(fn);
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        backgroundColor: WAPrimaryColor,
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(
              'assets/wa_app_logo.png',
              color: Colors.white,
              fit: BoxFit.cover,
              height: 100,
              width: 100,
            ).center(),
          ],
        ),
      ),
    );
  }
}
