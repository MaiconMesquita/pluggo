import 'validate.dart';

class ValidateInput {
  static String? fullName(String? name) {
    return !Validate.fullName(name) ? 'Por favor, digite um nome e sobrenome.' : null;
  }

  static String? name(String? name) {
    return !Validate.name(name) ? 'Por favor, digite um nome válido.' : null;
  }

  static String? rg(String? rg) {
    return !Validate.rg(rg) ? 'Por favor, digite um RG válido.' : null;
  }

  static String? cpf(String? cpf, {stripBeforeValidation = true}) {
    return !Validate.cpf(cpf, stripBeforeValidation: stripBeforeValidation) ? 'Por favor, digite um CPF válido.' : null;
  }

  static String? cnpj(String? cnpj, {stripBeforeValidation = true}) {
    return !Validate.cnpj(cnpj, stripBeforeValidation: stripBeforeValidation) ? 'Por favor, digite um CNPJ válido.' : null;
  }

  static String? document(String? value, {stripBeforeValidation = true}) {
    return !Validate.document(value, stripBeforeValidation: stripBeforeValidation) ? 'Por favor, digite um CPF/CNPJ válido.' : null;
  }

  static String? email(String? email, [bool allowTopLevelDomains = false, bool allowInternational = true]) {
    return !Validate.email(email, allowTopLevelDomains, allowInternational) ? 'Por favor, digite um e-mail válido.' : null;
  }

  static String? phone(String? phoneNumber) {
    return !Validate.phone(phoneNumber) ? 'Por favor, digite um número válido.' : null;
  }

  static String? otp(String? otp) {
    return !Validate.otp(otp) ? 'Por favor, digite um código válido.' : null;
  }

  static String? pixKey(String? key) {
    return !Validate.pixKey(key) ? 'Por favor, digite uma chave válida.' : null;
  }
/*
  static String? age(String? birthDate) {
    return !Validate.age(birthDate) ? 'Você deve ter pelo menos 18 anos.' : null;
  }

 */

  static String? currentDate(String? value) {
    return !Validate.currentDate(value) ? 'Por favor, digite uma data que seja maior que a data atual.' : null;
  }

  static String? citizenship(String? citizenship) {
    return !Validate.citizenship(citizenship) ? 'Por favor, digite uma nacionalidade válida.' : null;
  }

  static String? monthlyIncome(String? monthlyIncome) {
    return !Validate.valueNotEmptyAndZero(monthlyIncome) ? 'Por favor, digite uma renda mensal válida.' : null;
  }

  static String? description(String? description) {
    return !Validate.empty(description) ? 'Por favor, digite uma breve descrição.' : null;
  }

  static String? chargeValue(String? chargeValue) {
    return !Validate.valueNotEmptyAndZero(chargeValue) ? 'Por favor, digite um valor válido.' : null;
  }

  //  volume mensal de transações em cartões não vazio e maior que 10.000,00
  static String? saleTerminalMonthlyVolume(String? monthlyVolume) {
    return !Validate.saleTerminalMonthlyVolume(monthlyVolume) ? 'Por favor, digite um volume mensal superior a R\$10.000,00.' : null;
  }

  static String? patrimony(String? patrimony) {
    return !Validate.valueNotEmptyAndZero(patrimony) ? 'Por favor, digite um patrimônio válido.' : null;
  }
/*
  static String? pep(String? pep) {
    return !Validate.pep(pep) ? 'Não é possível abrir uma conta como pessoa politicamente exposta.' : null;
  }

 */
/*
  static String? sex(String? sex) {
    return !Validate.sex(sex) ? 'Selecione uma opção' : null;
  }

 */

  static String? cep(String? cep) {
    return !Validate.cep(cep) ? 'Por favor, digite um CEP válido.' : null;
  }

  static String? streetName(String? streetName) {
    return !Validate.streetName(streetName) ? 'Por favor, digite um logradouro válido.' : null;
  }

  static String? streetNumber(String? streetNumber) {
    return !Validate.streetNumber(streetNumber) ? 'Por favor, digite um número válido.' : null;
  }

  static String? complement(String? complement) {
    return !Validate.complement(complement) ? 'Por favor, digite um complemento válido.' : null;
  }

  static String? neighborhood(String? neighborhood) {
    return !Validate.neighborhood(neighborhood) ? 'Por favor, digite um bairro válido.' : null;
  }

  static String? city(String? city) {
    return !Validate.city(city) ? 'Por favor, digite um cidade válida.' : null;
  }

  static String? uf(String? uf) {
    return !Validate.uf(uf) ? 'Por favor, digite um estado válido.' : null;
  }

  static String? password(String? password) {
    return !Validate.password(password) ? 'Por favor, digite uma senha' : null;
  }

  static String? newPassword(String? password) {
    return !Validate.newPassword(password) ? 'A nova senha deve conter no mínimo 6 caracteres' : null;
  }

  static String? jwt(String? jwt) {
    return !Validate.jwt(jwt) ? 'Por favor, digite um código válido.' : null;
  }

  static String? bankNumber(String? bankNumber) {
    return !Validate.bankNumber(bankNumber) ? 'Por favor, digite um número de banco válido' : null;
  }

  static String? branch(String? branch) {
    return !Validate.branch(branch) ? 'Por favor, digite uma agência válida' : null;
  }

  static String? accountNumber(String? accountNumber) {
    return !Validate.accountNumber(accountNumber) ? 'Por favor, digite um número de conta válido' : null;
  }

  static String? accountDigit(String? accountDigit) {
    return !Validate.accountDigit(accountDigit) ? 'Por favor, digite um dígito de conta válido' : null;
  }

  static String? boletoDigitable(String? boletoDigitable) {
    return !Validate.boletoDigitable(boletoDigitable) ? 'Por favor, digite um código de boleto digitável válido' : null;
  }

  static String? creditCardNumber(String? creditCardNumber) {
    return !Validate.creditCardNumber(creditCardNumber) ? 'Por favor, digite um número de cartão válido' : null;
  }

  static String? cvc(String? cvc) {
    return !Validate.cvc(cvc) ? 'Por favor, digite um CVC válido' : null;
  }

  static String? receiveName(String? name) {
    return !Validate.empty(name) ? 'Por favor, digite um nome para a cobrança.' : null;
  }
/*
  static String? dueFine(String? value) {
    return !Validate.percentage(value) ? 'Insira uma multa válida (0,01 - 100,00%) a ser cobrada após o vencimento.' : null;
  }

  static String? dueFee(String? value) {
    return !Validate.percentage(value) ? 'Insira uma taxa de juros válida (0,01 - 100,00%) a ser cobrada mensalmente após o vencimento.' : null;
  }

  static String? percentageSaleTerminal(String? value) {
    return !Validate.percentage(value) ? 'Insira um percentual válido (0,01 - 100,00%) a ser cobrado.' : null;
  }

 */

  static String? pixCopyAndPaste(String? value) {
    return !Validate.pixCopyAndPaste(value) ? 'Por favor, digite um código válido.' : null;
  }

  //not empty and zero value
  static String? valueNotEmptyAndZero(String? value) {
    return !Validate.valueNotEmptyAndZero(value) ? 'Por favor, digite um valor válido.' : null;
  }

  static String? alwaysTrue (String? value) {
    return !Validate.alwaysTrue(value) ? 'Por favor, digite um valor válido.' : null;
  }
}