import 'package:flutter/material.dart';
import 'package:wallet_flutter/model/WalletAppModel.dart';
import 'package:wallet_flutter/screen/WABillPayScreen.dart';
import 'package:wallet_flutter/screen/WACreditCardScreen.dart';
import 'package:wallet_flutter/screen/WASendMoneyViaLoopScreen.dart';
import 'package:wallet_flutter/screen/WAVoucherScreen.dart';

List<String?> waMonthList = <String?>["Jan", "Feb", "Mar", "April", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
List<String?> waYearList = <String?>["1991", "1992", "1993", "1994", "1995", "1996", "1997", "1998", "1999", "2020", "2021"];
List<String?> waOrgList = <String?>["All", "Water", "Gas", "Electricity", "Internet", "Education", "Landline"];
List<String?> amountList = ["500", "1000", "800"];
List<String?> overViewList = ["All", "Weekly", "Yearly", "Daily", "Monthly"];

List<WAWalkThroughModel> waWalkThroughList() {
  List<WAWalkThroughModel> list = [];
  list.add(WAWalkThroughModel(title: "Easily Accessible", description: "Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.", image: 'assets/wa_walkthorugh.png'));
  list.add(WAWalkThroughModel(title: "Mange Anytime", description: "Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.", image: 'assets/wa_walkthorugh.png'));
  list.add(WAWalkThroughModel(title: "Safe Transaction", description: "Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.", image: 'assets/wa_walkthorugh.png'));

  return list;
}

List<WACardModel> waCardList() {
  List<WACardModel> cardList = [];
  cardList.add(WACardModel(balance: '\$12,00,000', cardNumber: '123 985 7654327', date: '03/23', color: Color(0xFF6C56F9)));
  cardList.add(WACardModel(balance: '\$12,23,000', cardNumber: '985 123 7654327', date: '25/23', color: Color(0xFFFF7426)));
  cardList.add(WACardModel(balance: '\$23,00,000', cardNumber: '765 123 9854327', date: '03/25', color: Color(0xFF26C884)));
  return cardList;
}

List<WAOperationsModel> waOperationList() {
  List<WAOperationsModel> operationModel = [];
  operationModel.add(WAOperationsModel(
    color: Color(0xFF6C56F9),
    title: 'Transfer',
    image: 'assets/wa_transfer.png',
    widget: WASendMoneyViaLoopScreen(),
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFFFF7426),
    title: 'Vouchers',
    image: 'assets/wa_voucher.png',
    widget: WAVoucherScreen(),
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFF6C56F9),
    title: 'Top Up',
    image: 'assets/wa_ticket.png',
    widget: WACreditCardScreen(),
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFF26C884),
    title: 'Bill Pay',
    image: 'assets/wa_bill_pay.png',
    widget: WABillPayScreen(),
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFF6C56F9),
    title: 'Rewards',
    image: 'assets/wa_ticket.png',
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFF26C884),
    title: 'Shopping',
    image: 'assets/wa_bill_pay.png',
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFFFF7426),
    title: 'Tickets',
    image: 'assets/wa_voucher.png',
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFF6C56F9),
    title: 'Budgets',
    image: 'assets/wa_voucher.png',
  ));
  operationModel.add(WAOperationsModel(
    color: Color(0xFFFF7426),
    title: 'Request',
    image: 'assets/wa_transfer.png',
  ));
  return operationModel;
}

List<WATransactionModel> waTransactionList() {
  List<WATransactionModel> transactionList = [];
  transactionList.add(WATransactionModel(
    color: Color(0xFFFF7426),
    title: 'Send Money to',
    image: 'assets/wa_bill_pay.png',
    balance: '-\$20,000',
    name: 'James',
    time: 'Today 5:30 PM',
  ));
  transactionList.add(WATransactionModel(
    color: Color(0xFF26C884),
    title: 'Salary from',
    image: 'assets/wa_voucher.png',
    balance: '+\$50,000',
    name: 'Unbox Digital',
    time: 'Today 6:30 PM',
  ));
  return transactionList;
}

List<WARecentPayeesModel> waRecentPayeesList() {
  List<WARecentPayeesModel> list = [];
  list.add(WARecentPayeesModel(image: 'https://www.vrsiddhartha.ac.in/me/wp-content/uploads/learn-press-profile/4/172522ec1028ab781d9dfd17eaca4427.jpg', name: 'John', number: '123456789'));
  list.add(WARecentPayeesModel(image: 'https://royalrajtravels.com/image/1613583503main-qimg-6291c3a117fc230c82785148baef7eed.jpg', name: 'Rose', number: '78571237'));
  list.add(WARecentPayeesModel(image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5yt4pfdz3-lacajgUY5xuRuciElEaMZa9luc29Vgx2oVLDQceaFmxgcUXRzU-IfTZcWA&usqp=CAU', name: 'Willam', number: '456123522'));
  list.add(WARecentPayeesModel(image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5yt4pfdz3-lacajgUY5xuRuciElEaMZa9luc29Vgx2oVLDQceaFmxgcUXRzU-IfTZcWA&usqp=CAU', name: 'Willam', number: '5568553522'));
  list.add(WARecentPayeesModel(image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5yt4pfdz3-lacajgUY5xuRuciElEaMZa9luc29Vgx2oVLDQceaFmxgcUXRzU-IfTZcWA&usqp=CAU', name: 'Willam', number: '852123522'));
  list.add(WARecentPayeesModel(image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIuNPlLLXKdjlBivtZzQxsq-hW9E6YbooFXqDqST7AfuSHGcN45DIDTi5qeLOQHNrNR9g&usqp=CAU', name: 'Rose', number: '4561222222'));
  list.add(WARecentPayeesModel(image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSw9jzCKDNeX4QbAQOAABXMML3djP3ZVEv1-rpbKgfyNCshsC-zhB3Ta2JXdMxvvSOFszg&usqp=CAU', name: 'Bella', number: '98561233'));
  return list;
}

List<WABillPayModel> waBillPayList() {
  List<WABillPayModel> list = [];
  list.add(WABillPayModel(title: 'Water', color: Colors.blue, image: 'assets/wa_water.png'));
  list.add(WABillPayModel(title: 'Gas', color: Color(0xFFFF7426), image: 'assets/wa_gas.png'));
  list.add(WABillPayModel(title: 'Electricity', color: Colors.yellow, image: 'assets/wa_electricity.png'));
  list.add(WABillPayModel(title: 'Internet', color: Colors.pink, image: 'assets/wa_internet.png'));
  list.add(WABillPayModel(title: 'Education', color: Colors.green, image: 'assets/wa_education.png'));
  list.add(WABillPayModel(title: 'Landline', color: Colors.lightBlueAccent, image: 'assets/wa_landline.png'));
  return list;
}

List<WAOrganizationModel> waOrganizationList() {
  List<WAOrganizationModel> list = [];
  list.add(WAOrganizationModel(color: Colors.blue, image: 'assets/wa_water.png', title: 'Eco Water', subTitle: 'Water Supplier'));
  list.add(WAOrganizationModel(color: Colors.yellow, image: 'assets/wa_electricity.png', title: 'bolt Powergrid', subTitle: 'Electricity Supplier'));
  list.add(WAOrganizationModel(color: Colors.pink, image: 'assets/wa_internet.png', title: 'Nina Network', subTitle: 'Internet Supplier'));
  list.add(WAOrganizationModel(color: Colors.yellow, image: 'assets/wa_electricity.png', title: 'Green Energy ', subTitle: 'Electricity Supplier'));
  list.add(WAOrganizationModel(color: Colors.pink, image: 'assets/wa_internet.png', title: 'Express Internet', subTitle: 'Internet Supplier'));
  return list;
}

List<WABillPayModel> waSelectBillList() {
  List<WABillPayModel> list = [];
  list.add(WABillPayModel(title: 'CityBank', image: 'assets/wa_city_bank_image.png'));
  list.add(WABillPayModel(title: 'Bank of America', image: 'assets/wa_bank_of_america.png'));
  list.add(WABillPayModel(title: 'Yes Bank', image: 'assets/wa_yes_bank.png'));
  return list;
}

List<WACardModel> waSendViaCardList() {
  List<WACardModel> list = [];
  list.add(WACardModel(balance: '\$12,00,000', cardNumber: '123 985 7654327', date: '03/23', color: Color(0xFF6C56F9), image: 'assets/wa_card.png'));
  list.add(WACardModel(balance: '\$12,23,000', cardNumber: '985 123 7654327', date: '25/23', color: Color(0xFFFF7426), image: 'assets/wa_card.png'));
  list.add(WACardModel(balance: '\$23,00,000', cardNumber: '765 123 9854327', date: '03/25', color: Color(0xFF26C884), image: 'assets/wa_card.png'));
  return list;
}

List<WAWalletUserModel> waWalletUserList() {
  List<WAWalletUserModel> list = [];
  list.add(WAWalletUserModel(image: "https://www.vrsiddhartha.ac.in/me/wp-content/uploads/learn-press-profile/4/172522ec1028ab781d9dfd17eaca4427.jpg"));
  list.add(WAWalletUserModel(image: "https://royalrajtravels.com/image/1613583503main-qimg-6291c3a117fc230c82785148baef7eed.jpg"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5yt4pfdz3-lacajgUY5xuRuciElEaMZa9luc29Vgx2oVLDQceaFmxgcUXRzU-IfTZcWA&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXs_iIewEiaZ3tXb6n6VgaUIONS0B0HjwsqcvA3-EnnaNm0BwX216u2dZl2QTHnP7VOIU&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTH7wtiaB5F3B2oaF5699EJCNEtPnjD57ERWKTMjN0h-gpRxrFQ1u68HzFFT3eYJFFNLr4&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTH7wtiaB5F3B2oaF5699EJCNEtPnjD57ERWKTMjN0h-gpRxrFQ1u68HzFFT3eYJFFNLr4&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTH7wtiaB5F3B2oaF5699EJCNEtPnjD57ERWKTMjN0h-gpRxrFQ1u68HzFFT3eYJFFNLr4&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTH7wtiaB5F3B2oaF5699EJCNEtPnjD57ERWKTMjN0h-gpRxrFQ1u68HzFFT3eYJFFNLr4&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTH7wtiaB5F3B2oaF5699EJCNEtPnjD57ERWKTMjN0h-gpRxrFQ1u68HzFFT3eYJFFNLr4&usqp=CAU"));
  list.add(WAWalletUserModel(image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTH7wtiaB5F3B2oaF5699EJCNEtPnjD57ERWKTMjN0h-gpRxrFQ1u68HzFFT3eYJFFNLr4&usqp=CAU"));
  return list;
}

List<WATransactionModel> waCategoriesList() {
  List<WATransactionModel> list = [];
  list.add(WATransactionModel(color: Color(0xFF26C884), title: 'Clothes', image: 'assets/wa_clothes.png', balance: '-\$10,000', time: 'Today 12:30 PM'));
  list.add(WATransactionModel(color: Color(0xFFFF7426), title: 'Grocery', image: 'assets/wa_food.png', balance: '-\$8,000', time: 'Today 1:02 PM'));
  return list;
}

List<WAVoucherModel> waVouchersList() {
  List<WAVoucherModel> list = [];
  list.add(WAVoucherModel(image: 'assets/wa_zara.png', title: 'ZARA Fashion', discountText: '10% Off', expireTime: 'Expires on 15 June', pointsText: 'For 1500 points'));
  list.add(WAVoucherModel(image: 'assets/wa_macdonals.jpeg', title: 'Mcdonald\'s', discountText: '5% Off', expireTime: 'Expires on 20 June', pointsText: 'For 600 points'));
  list.add(WAVoucherModel(image: 'assets/wa_macdonals.jpeg', title: 'Mcdonald\'s', discountText: '5% Off', expireTime: 'Expires on 20 June', pointsText: 'For 600 points'));
  list.add(WAVoucherModel(image: 'assets/wa_zara.png', title: 'ZARA Fashion', discountText: '10% Off', expireTime: 'Expires on 15 June', pointsText: 'For 1500 points'));
  list.add(WAVoucherModel(image: 'assets/wa_zara.png', title: 'ZARA Fashion', discountText: '10% Off', expireTime: 'Expires on 15 June', pointsText: 'For 1500 points'));
  list.add(WAVoucherModel(image: 'assets/wa_macdonals.jpeg', title: 'Mcdonald\'s', discountText: '5% Off', expireTime: 'Expires on 20 June', pointsText: 'For 600 points'));
  list.add(WAVoucherModel(image: 'assets/wa_macdonals.jpeg', title: 'Mcdonald\'s', discountText: '5% Off', expireTime: 'Expires on 20 June', pointsText: 'For 600 points'));
  list.add(WAVoucherModel(image: 'assets/wa_zara.png', title: 'ZARA Fashion', discountText: '10% Off', expireTime: 'Expires on 15 June', pointsText: 'For 1500 points'));
  return list;
}
