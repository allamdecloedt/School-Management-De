import 'package:flutter/material.dart';
import 'package:flutter/painting.dart';
import 'package:flutter/rendering.dart';
import 'package:nb_utils/nb_utils.dart';
import 'package:wallet_flutter/screen/WAHomeScreen.dart';
import 'package:wallet_flutter/screen/WAMyProfileScreen.dart';
import 'package:wallet_flutter/screen/WAQrScannerScreen.dart';
import 'package:wallet_flutter/screen/WAStatisticsScreen.dart';
import 'package:wallet_flutter/screen/WAWalletScreen.dart';
import 'package:wallet_flutter/utils/WAColors.dart';

class WADashboardScreen extends StatefulWidget {
  static String tag = '/WADashboardScreen';

  @override
  WADashboardScreenState createState() => WADashboardScreenState();
}

class WADashboardScreenState extends State<WADashboardScreen> {
  int _selectedIndex = 0;
  var _pages = <Widget>[
    WAHomeScreen(),
    WAStatisticScreen(),
    WAWalletScreen(),
    WAMyProfileScreen(),
  ];

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
    return Scaffold(
      body: _pages.elementAt(_selectedIndex),
      floatingActionButtonLocation: FloatingActionButtonLocation.miniCenterDocked,
      floatingActionButton: Padding(
        padding: EdgeInsets.all(6.0),
        child: FloatingActionButton(
          backgroundColor: WAPrimaryColor,
          child: Icon(Icons.qr_code_scanner_sharp, color: Colors.white),
          onPressed: () {
            push(WAQrScannerScreen());
          },
        ),
      ),
      bottomNavigationBar: BottomAppBar(
        shape: CircularNotchedRectangle(),
        clipBehavior: Clip.antiAlias,
        child: BottomNavigationBar(
          backgroundColor: Colors.white,
          currentIndex: _selectedIndex,
          onTap: (index) {
            setState(() {
              _selectedIndex = index;
            });
          },
          type: BottomNavigationBarType.fixed,
          selectedItemColor: WAPrimaryColor,
          unselectedItemColor: Colors.grey,
          showSelectedLabels: false,
          showUnselectedLabels: false,
          items: <BottomNavigationBarItem>[
            BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
            BottomNavigationBarItem(icon: Icon(Icons.date_range), label: 'Statistics'),
            BottomNavigationBarItem(icon: Icon(Icons.wallet_giftcard), label: 'Wallet'),
            BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
          ],
        ),
      ),
    );
  }
}
