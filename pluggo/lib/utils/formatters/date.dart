import 'package:intl/intl.dart';

class DateFormatter {
  static String formatDateAgo(DateTime date) {
    final now = DateTime.now();
    final difference = now.difference(date);

    if (difference.inMinutes < 1) {
      return 'Agora';
    } else if (difference.inMinutes < 60) {
      final minutes = difference.inMinutes;
      return '$minutes ${minutes == 1 ? 'minuto' : 'minutos'} atrás';
    } else if (difference.inHours < 24) {
      final hours = difference.inHours;
      return '$hours ${hours == 1 ? 'hora' : 'horas'} atrás';
    } else if (difference.inDays < 7) {
      final days = difference.inDays;
      return '$days ${days == 1 ? 'dia' : 'dias'} atrás';
    } else {
      return '${date.day}/${date.month}/${date.year}';
    }
  }

  static String formatDateTime(String dateTimeString) {
    // Parse a string de data para um objeto DateTime
    final dateTime = DateTime.parse(dateTimeString);

    // Defina o formato desejado (exemplo: dd/MM/yyyy HH:mm)
    final formatterDays = DateFormat('dd/MM/yyyy');
    final formatterTime = DateFormat('HH:mm');
    final date = formatterDays.format(dateTime);
    final time = formatterTime.format(dateTime);

    // Retorna a data formatada
    return '$date às $time';
  }

  static String formatDDMMYY(String dateTimeString) {
    // Parse a string de data para um objeto DateTime
    final dateTime = DateTime.parse(dateTimeString);

    // Defina o formato desejado (exemplo: dd/MM/yyyy HH:mm)
    final formatterDays = DateFormat('dd/MM/yy');
    final date = formatterDays.format(dateTime);

    // Retorna a data formatada
    return date;
  }
}