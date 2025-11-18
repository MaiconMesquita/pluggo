import 'package:flutter/widgets.dart';

class SignUpInput {
  final TextEditingController cpfController = TextEditingController();
  final TextEditingController rgController = TextEditingController();
  final TextEditingController nameController = TextEditingController();
  final TextEditingController motherNameController = TextEditingController();
  final TextEditingController fatherNameController = TextEditingController();
  final TextEditingController birthDateController = TextEditingController();
  String sex = '';
  String civilState = '';
  String ssp = '';
  String uf = '';
  String nationality = '';
  DateTime? birthDate;

  String installment = '1';
  final TextEditingController citizenshipController = TextEditingController();
  final TextEditingController patrimonyController = TextEditingController();
  final TextEditingController monthlyIncomeController = TextEditingController();
  String isPep = '';
  final TextEditingController emailController = TextEditingController();
  final TextEditingController emailCodeController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController smsCodeController = TextEditingController();
}