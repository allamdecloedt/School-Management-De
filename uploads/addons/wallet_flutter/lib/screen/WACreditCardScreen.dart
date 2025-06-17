import 'package:flutter/material.dart';
import 'package:wallet_flutter/component/PurchaseButton.dart';

class WACreditCardScreen extends StatefulWidget {
  static String tag = '/WACreditCardScreen';

  @override
  WACreditCardScreenState createState() => WACreditCardScreenState();
}

class WACreditCardScreenState extends State<WACreditCardScreen> {
  @override
  void initState() {
    super.initState();
    init();
  }

  init() async {
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
