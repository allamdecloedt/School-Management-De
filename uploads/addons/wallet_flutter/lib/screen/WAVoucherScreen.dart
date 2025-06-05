import 'package:flutter/material.dart';
import 'package:wallet_flutter/component/PurchaseButton.dart';

class WAVoucherScreen extends StatefulWidget {
  static String tag = '/WAVoucherScreen';

  @override
  WAVoucherScreenState createState() => WAVoucherScreenState();
}

class WAVoucherScreenState extends State<WAVoucherScreen> {
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
    return SafeArea(
      child: PurchaseButton(),
    );
  }
}
