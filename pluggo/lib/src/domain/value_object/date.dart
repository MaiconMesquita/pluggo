import 'package:intl/intl.dart';

import '../../../utils/date_utils.dart';
import '../../../utils/formatters/date.dart';

class Date {
  final DateTime _value;

  Date(this._value);

  DateTime get value => _value;
  String get toJson => DateFormat('yyyy-MM-dd').format(_value);
  String get toDDMM => DateFormat('dd/MM').format(_value);
  String get toDD => DateFormat('dd').format(_value);
  String get toMM => DateFormat('MM').format(_value);
  String get toMonth => MyDateUtils.getMonthName(int.parse(toMM));
  String get toDayMonth => '${DateFormat('dd').format(_value)} ${MyDateUtils.getMonthPrev(int.parse(toMM))}';
  String get toFull => '${DateFormat('dd').format(_value)} de ${MyDateUtils.getMonthName(int.parse(toMM))} de ${DateFormat('yyyy').format(_value)}';
  String get formatted => DateFormat('dd/MM/yyyy').format(_value);
  String get formattedWithHours => '${DateFormat('dd/MM/yyyy').format(_value)} às ${DateFormat('HH:mm').format(_value)}';
  String get onlyHours => DateFormat('HH:mm').format(_value);
  String get newOnlyHours => '${DateFormat('HH').format(_value)}h${DateFormat('mm').format(_value)}';
  String get formattedAgo => DateFormatter.formatDateAgo(_value);
  //String get toWeekday => DateFormat('EEEE', 'pt_BR').format(_value);
  String get toWeekday => 'Segunda-feira';
}