import 'validator_current_date.dart';
import 'validator_name.dart';
//import 'package:app/src/utils/validators/validator_percentage.dart';
//import 'package:app/src/utils/validators/validator_random_pix_key.dart';
//import 'package:app/src/utils/validators/validator_sex.dart';
//import 'package:app/src/utils/validators/validator_age.dart';
import 'validator_email.dart';
//import 'package:app/src/utils/validators/validator_pep.dart';
import 'validator_phone.dart';
import 'validator_range.dart';
import 'validator_value.dart';
import 'package:brasil_fields/brasil_fields.dart';

class Validate {
  static bool empty(String? value) {
    return RangeValidator.isValid(value, 1, 200, clearNonNumber: false);
  }

  static bool fullName(String? name) {
    return NameValidator.isValid(name);
  }

  static bool name(String? name) {
    return ValueValidator.isValid(name, 5, 10000000, clearNonNumber: false);
  }

  static bool cpf(String? cpf, {stripBeforeValidation = true}) {
    return CPFValidator.isValid(cpf, stripBeforeValidation: stripBeforeValidation);
  }

  static bool rg(String? rg) {
    if (rg == null || rg.isEmpty) return false;

    // Clean input (remove dots, hyphens, and spaces)
    String cleanedRG = rg.replaceAll(RegExp(r'[.\-\s]'), '');

    // Check if it has 7 to 9 digits or ends with an optional letter
    return RegExp(r'^\d{9}[A-Za-z]?$').hasMatch(cleanedRG);
  }

  static bool cnpj(String? cnpj, {stripBeforeValidation = true}) {
    return CNPJValidator.isValid(cnpj, stripBeforeValidation: stripBeforeValidation);
  }

  static bool document(String? value, {stripBeforeValidation = true}) {
    bool isCPFValid = cpf(value, stripBeforeValidation: stripBeforeValidation);
    bool isCNPJValid = cnpj(value, stripBeforeValidation: stripBeforeValidation);
    if (isCPFValid == true || isCNPJValid == true) {
      return true;
    } else {
      return false;
    }
  }

  static bool email(String? email, [bool allowTopLevelDomains = false, bool allowInternational = true]) {
    return EmailValidator.isValid(email, allowTopLevelDomains, allowInternational);
  }

  static bool phone(String? phoneNumber) {
    return BrazilianPhoneNumberValidator.isValid(phoneNumber);
  }

  static bool otp(String? otp) {
    return RangeValidator.isValid(otp, 5, 10, clearNonNumber: false);
  }

  static bool pixKey(String? pixKey) {
    bool isPhone = BrazilianPhoneNumberValidator.isValid(pixKey);
    bool isEmail = EmailValidator.isValid(pixKey, false, true);
    bool isCPF = cpf(pixKey, stripBeforeValidation: true);
    if (isPhone == true || isEmail == true || isCPF == true) {
      return true;
    } else {
      return false;
    }
  }
/*
  static bool age(String? birthDate) {
    return AgeValidator.isValid(birthDate);
  }

 */

  static bool currentDate(String? currentDate) {
    return CurrentDateValidator.isValid(currentDate);
  }

  static bool citizenship(String? citizenship) {
    return RangeValidator.isValid(citizenship, 1, 20, clearNonNumber: false);
  }

  static bool monthlyIncome(String? monthlyIncome) {
    return RangeValidator.isValid(monthlyIncome, 1, 100, clearNonNumber: true);
  }

  static bool patrimony(String? patrimony) {
    return RangeValidator.isValid(patrimony, 1, 100, clearNonNumber: true);
  }

  static bool saleTerminalMonthlyVolume(String? value) {
    return ValueValidator.isValid(value, 5, 10000000, clearNonNumber: true);
  }

  static bool valueNotEmptyAndZero(String? value) {
    return ValueValidator.isValid(value, 1, 100, clearNonNumber: true);
  }

  static bool alwaysTrue(String? value) {
    return true;
  }
/*
  static bool pep(String? pep) {
    return PepValidator.isValid(pep);
  }

  static bool sex(String? sex) {
    return SexValidator.isValid(sex);
  }

 */

  static bool cep(String? cep) {
    return RangeValidator.isValid(cep, 8, 100, clearNonNumber: true);
  }

  static bool streetName(String? streetName) {
    return RangeValidator.isValid(streetName, 1, 100, clearNonNumber: false);
  }

  static bool streetNumber(String? streetNumber) {
    return RangeValidator.isValid(streetNumber, 1, 100, clearNonNumber: false);
  }

  static bool complement(String? complement) {
    return RangeValidator.isValid(complement, 0, 100, clearNonNumber: false);
  }

  static bool neighborhood(String? neighborhood) {
    return RangeValidator.isValid(neighborhood, 1, 100, clearNonNumber: false);
  }

  static bool city(String? city) {
    return RangeValidator.isValid(city, 1, 100, clearNonNumber: false);
  }

  static bool uf(String? uf) {
    return RangeValidator.isValid(uf, 2, 2, clearNonNumber: false);
  }

  static bool password(String? password) {
    return RangeValidator.isValid(password, 1, 100, clearNonNumber: false);
  }

  static bool newPassword(String? password) {
    return RangeValidator.isValid(password, 6, 100, clearNonNumber: false);
  }

  static bool jwt(String? jwt) {
    return RangeValidator.isValid(jwt, 1, 500, clearNonNumber: false);
  }

  static bool bankNumber(String? bankNumber) {
    return RangeValidator.isValid(bankNumber, 3, 3, clearNonNumber: true);
  }

  static bool branch(String? branch) {
    return RangeValidator.isValid(branch, 4, 4, clearNonNumber: true);
  }

  static bool accountNumber(String? accountNumber) {
    return RangeValidator.isValid(accountNumber, 4, 10, clearNonNumber: true);
  }

  static bool accountDigit(String? accountDigit) {
    return RangeValidator.isValid(accountDigit, 1, 1, clearNonNumber: true);
  }

  static bool boletoDigitable(String? accountDigit) {
    return RangeValidator.isValid(accountDigit, 44, 48, clearNonNumber: true);
  }

  static bool creditCardNumber(String? creditCardNumber) {
    return RangeValidator.isValid(creditCardNumber, 13, 16, clearNonNumber: true);
  }

  static bool cvc(String? cvc) {
    return RangeValidator.isValid(cvc, 3, 4, clearNonNumber: true);
  }
/*
  static bool percentage(String? percentage) {
    return PercentageValidator.isValid(percentage);
  }

  static bool ramdomPixKey(String? pixKey) {
    return RamdomPixKeyValidator.isValid(pixKey);
  }

 */

  static bool pixCopyAndPaste(String? value) {
    if (value == null) {
      return false;
    }
    return value.startsWith("000201");
  }
}