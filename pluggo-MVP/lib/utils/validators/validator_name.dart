class NameValidator {
  static bool isValid(String? value) {
    const double minimumNameSize = 5;
    const double maximumNameSize = 100;
    const double minimumWords = 2;

    if (value == null) return false;

    final words = value.split(' ');
    if (value.length < minimumNameSize || words.length < minimumWords || value.length > maximumNameSize) {
      return false;
    }

    return true;
  }
}