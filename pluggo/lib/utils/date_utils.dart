class MyDateUtils {
  static String getMonthName(int monthNumber) {
    switch (monthNumber) {
      case 1:
        return 'Janeiro';
      case 2:
        return 'Fevereiro';
      case 3:
        return 'Março';
      case 4:
        return 'Abril';
      case 5:
        return 'Maio';
      case 6:
        return 'Junho';
      case 7:
        return 'Julho';
      case 8:
        return 'Agosto';
      case 9:
        return 'Setembro';
      case 10:
        return 'Outubro';
      case 11:
        return 'Novembro';
      case 12:
        return 'Dezembro';
      default:
        return '';
    }
  }

  static String getMonthPrev(int monthNumber) {
    switch (monthNumber) {
      case 1:
        return 'Jan.';
      case 2:
        return 'Fev.';
      case 3:
        return 'Mar.';
      case 4:
        return 'Abr.';
      case 5:
        return 'Mai.';
      case 6:
        return 'Jun.';
      case 7:
        return 'Jul.';
      case 8:
        return 'Ago.';
      case 9:
        return 'Set.';
      case 10:
        return 'Out.';
      case 11:
        return 'Nov.';
      case 12:
        return 'Dez.';
      default:
        return '';
    }
  }


  static List<String> getMonths() {
    return ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
  }

  static List<String> getCreditCardYears() {
    int currentYear = DateTime.now().year;
    return List.generate(10, (index) {
      return (currentYear + index).toString().substring(2);
    });
  }
}