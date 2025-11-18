import '../formatters/formatter.dart';

class BrazilianPhoneNumberValidator {
  static bool isValid(String? phoneNumber) {
    if (phoneNumber == null || phoneNumber.isEmpty) {
      return false;
    }

    phoneNumber = Formatter.removeNonNumbers(phoneNumber);
    if (phoneNumber.startsWith('55')) phoneNumber = phoneNumber.substring(2);

    if (phoneNumber.length != 11) {
      return false;
    }

    final ddd = phoneNumber.substring(0, 2);

    final validDDDs = [
      '11',
      '12',
      '13',
      '14',
      '15',
      '16',
      '17',
      '18',
      '19',
      '21',
      '22',
      '24',
      '27',
      '28',
      '31',
      '32',
      '33',
      '34',
      '35',
      '37',
      '38',
      '41',
      '42',
      '43',
      '44',
      '45',
      '46',
      '47',
      '48',
      '49',
      '51',
      '53',
      '54',
      '55',
      '61',
      '62',
      '63',
      '64',
      '65',
      '66',
      '67',
      '68',
      '69',
      '71',
      '73',
      '74',
      '75',
      '77',
      '79',
      '81',
      '82',
      '83',
      '84',
      '85',
      '86',
      '87',
      '88',
      '89',
      '91',
      '92',
      '93',
      '94',
      '95',
      '96',
      '97',
      '98',
      '99'
    ];

    if (!validDDDs.contains(ddd)) {
      return false;
    }

    final firstDigit = phoneNumber.substring(2, 3);

    if (firstDigit != '9') {
      return false;
    }

    return true;
  }
}