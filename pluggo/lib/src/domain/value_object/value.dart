import 'package:brasil_fields/brasil_fields.dart';
import '../../../utils/formatters/formatter.dart';

class Value {
  String _value;
  Value(this._value, {bool isCent = true, bool isInt = false}) {
    if (isInt) {
      if (RegExp(r'^\d+\.\d$').hasMatch(_value)) {
        _value = (double.parse(_value) * 10).toString(); // Adiciona um zero no final para corrigir a conversão
      }
  }
    if (isCent) {
      _value = Formatter.removeNonNumbers(_value);
    } else {
      _value = Formatter.removeNonNumbers(_value);
      if (_value.isNotEmpty) {
        final valueInt = int.parse(_value) * 100;
        _value = valueInt.toString();
      }
    }
  }


  String get value => _value;
  int get valueAsInt => int.parse(_value);
  double get valueAsDouble => double.parse(_value);
  String get valueFormatted => _value.isNotEmpty ? UtilBrasilFields.obterReal(valueAsInt / 100, moeda: false, decimal: 0) : '';
  String get valueFormattedWithCents => _value.isNotEmpty ? UtilBrasilFields.obterReal(valueAsInt / 100, moeda: false) : '';
  String get valueReal => value.isNotEmpty ? UtilBrasilFields.obterReal(valueAsInt / 100, decimal: 0) : '';
  String get valueRealWithCents => value.isNotEmpty ? UtilBrasilFields.obterReal(valueAsInt / 100) : '';
}