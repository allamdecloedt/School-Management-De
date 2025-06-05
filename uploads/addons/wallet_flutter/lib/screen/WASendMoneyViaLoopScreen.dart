import 'package:flutter/material.dart';
import 'package:wallet_flutter/component/PurchaseButton.dart';

class WASendMoneyViaLoopScreen extends StatefulWidget {
  static String tag = '/WASendMoneyViaLoopScreen';

  @override
  WASendMoneyViaLoopScreenState createState() => WASendMoneyViaLoopScreenState();
}

class WASendMoneyViaLoopScreenState extends State<WASendMoneyViaLoopScreen> {
  @override
  void initState() {
    super.initState();
    init();
  }

  Future<void> init() async {}

  @override
  void setState(fn) {
    if (mounted) super.setState(fn);
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: PurchaseButton(),
    );
  }
}
