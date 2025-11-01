class CurrentDateValidator {
  static bool isValid(String? date) {
    date = date?.replaceAll(RegExp(r'[^0-9]'), '');

    if (date == null || date.length != 8) {
      return false;
    }

    // Extrair o dia, mês e ano da data de nascimento
    var day = int.parse(date.substring(0, 2));
    var month = int.parse(date.substring(2, 4));
    var year = int.parse(date.substring(4));

    // Criar um objeto DateTime com a data de nascimento
    var dateTime = DateTime(year, month, day);

    // Obter a data atual
    var currentDate = DateTime.now();

    // Verificar se a data de nascimento é menor ou igual à data atual
    if (dateTime.isAfter(currentDate) || dateTime.isAtSameMomentAs(currentDate)) {
      return true;
    }

    return false;
  }
}