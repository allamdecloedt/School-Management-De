import 'package:flutter/material.dart';
import 'package:wallet_flutter/component/PurchaseButton.dart';

class WAQrScannerScreen extends StatefulWidget {
  static String tag = '/WAQrScannerScreen';

  @override
  WAQrScannerScreenState createState() => WAQrScannerScreenState();
}

class WAQrScannerScreenState extends State<WAQrScannerScreen> {
  final GlobalKey qrKey = GlobalKey(debugLabel: 'QR');

  @override
  void reassemble() {
    super.reassemble();
  }

  @override
  void initState() {
    super.initState();
    init();
  }

  init() async {}

  @override
  void dispose() {
    super.dispose();
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
