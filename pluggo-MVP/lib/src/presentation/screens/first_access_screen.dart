
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/presentation/styles/spacings.dart';
import 'package:pluggo/src/presentation/widgets/inputs/custom_textfield.dart';
import 'package:pluggo/utils/validators/validate_input.dart';
import 'package:provider/provider.dart';

import '../../domain/enum/color_type.dart';
import '../../provider/style/home_provider.dart';
import '../layout/layout_first_access.dart';
import '../provider/theme_provider.dart';
import '../widgets/button/custom_loading_button.dart';
import '../widgets/inputs/horizontal_select.dart';

@RoutePage()
class FirstAccessScreen extends StatelessWidget {
  const FirstAccessScreen({super.key});
  //ShowPopUp.showSelectServiceTermPopUp(selectedServiceType: ServiceType.fee );
//termos de uso
  @override
  Widget build(BuildContext context) {
    final formKeys = List.generate(2, (index) => GlobalKey<FormState>());

    return Consumer<HomeProvider1>(
      builder: (context, provider, child) {
        return LayoutFirstAccess(
          showTokenButton: true,
          loginBackground: ThemeProvider.of(context).alwaysShowLoginBackground,
          catchPhrase: "PlugGo!",
          child: Column(
            children: [
              SizedBox(height: HeightSpacing.large,),
              Column(
                children: [
                  CustomTextField(
                    formKey: formKeys[0],
                    keyboardType: TextInputType.emailAddress,
                    textCapitalization: TextCapitalization.none,
                    textInputAction: TextInputAction.next,
                    validator: (email) {
                      return ValidateInput.email(email);
                    },
                    controller: provider.authInput.emailController,
                    labelText: 'E-mail',
                    descriptionText: 'E-mail',
                  ),

                  CustomTextField(
                    formKey: formKeys[1],
                    keyboardType: TextInputType.text,
                    textCapitalization: TextCapitalization.none,
                    controller: provider.authInput.passwordController,
                    labelText: 'senha',
                    descriptionText: 'Senha',
                    obscureText: true,
                    validator: (password) {
                      return ValidateInput.password(password);
                    },
                  ),
                  HorizontalToggleWidget(
                    title: 'Tipo',
                    options: [
                      HorizontalSelectOption(label: "Motorista", value: "driver"),
                      HorizontalSelectOption(label: "Anfitrião", value: "host"),
                    ],
                    selectedValue: provider.entity,
                    onOptionSelected: (value) {
                      provider.setEntity(value);
                    },
                  ),
                ],
              ),
              CustomRoundedLoadingButton(
                  color: ColorType.secondary,
                  controller: provider.btnController,
                  onPressedCallback: provider.signing,
                  isValid: () => formKeys[0].currentState!.validate() &&
                      formKeys[1].currentState!.validate(),
                  text: 'Acessar conta'
              ),

              CustomRoundedLoadingButton(
                  color: ColorType.transparent,
                  onPressedCallback: provider.goToSignup,
                  border: true,
                  isValid: () => true,
                  text: 'Abrir minha conta'
              ),
            ],
          ),
        );
      },
    );
  }
}