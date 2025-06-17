import 'package:flutter/material.dart';
import 'package:wallet_flutter/component/PurchaseButton.dart';

class WABillPayScreen extends StatefulWidget {
  static String tag = '/WABillPayScreen';

  @override
  WABillPayScreenState createState() => WABillPayScreenState();
}

class WABillPayScreenState extends State<WABillPayScreen> {
  @override
  void initState() {
    super.initState();
    init();
  }

  Future<void> init() async {
    //
  }

  @override
  void setState(fn) {
    if (mounted) super.setState(fn);
  }

  @override
  Widget build(BuildContext context) {
    return PurchaseButton();
  }
}
