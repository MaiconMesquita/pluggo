import 'package:intl/intl.dart';

class ValueFormatter {
  static int brlToInt(String value) {
    String cleanValue = value.replaceAll('R\$', '').replaceAll(' ', '');
    int numericValue = int.parse(cleanValue.replaceAll('.', '').replaceAll(',', ''));
    return numericValue;
  }

  static String centsToRealBRL(String value) {
    double realValue = double.parse(value) / 100;
    NumberFormat brlFormat = NumberFormat('#,##0.00', 'pt_BR');
    return brlFormat.format(realValue);
  }
}