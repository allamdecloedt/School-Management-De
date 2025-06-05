import 'package:flutter/material.dart';
import 'package:wallet_flutter/component/PurchaseButton.dart';
import 'package:wallet_flutter/model/WalletAppModel.dart';
import 'package:wallet_flutter/utils/WADataGenerator.dart';

class WAWalletScreen extends StatefulWidget {
  static String tag = '/WAWalletScreen';

  @override
  WAWalletScreenState createState() => WAWalletScreenState();
}

class WAWalletScreenState extends State<WAWalletScreen> {
  List<WAWalletUserModel> walletUserList = waWalletUserList();
  List<WACardModel> walletList = waCardList();
  List<WATransactionModel> transactionList = waTransactionList();

  PageController? pageController;
  int currentPosition = 1;

  @override
  void initState() {
    super.initState();
    init();
  }

  init() async {
    pageController = PageController(initialPage: currentPosition, viewportFraction: 0.8);
  }

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
