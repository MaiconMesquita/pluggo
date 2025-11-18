
import 'package:auto_route/auto_route.dart';
import 'package:brasil_fields/brasil_fields.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:pluggo/src/presentation/layout/layout_default1.dart';
import 'package:pluggo/src/presentation/styles/spacings.dart';
import 'package:pluggo/src/presentation/widgets/appbar/header_default.dart';
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
class FormsScreen extends StatelessWidget {
  const FormsScreen({super.key});
  @override
  Widget build(BuildContext context) {
    final formKeys = List.generate(3, (index) => GlobalKey<FormState>());

    return Consumer<HomeProvider1>(
      builder: (context, provider, child) {
        return DefaultLayout1(
            appBar: const HeaderDefault(
              closeButton: false,
              title: "Adicionar produtos",
            ),
            isBackgroundDark: true,
            child: Column(
                children: [
                  SizedBox(height: HeightSpacing.large,),
                  Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      CustomTextField(
                        formKey: formKeys[0],
                        keyboardType: TextInputType.text,
                        textInputAction: TextInputAction.next,
                        controller: provider.signUpInput.nameController,
                        inputFormatters: [LengthLimitingTextInputFormatter(100)],
                        validator: (name) {
                          return ValidateInput.fullName(name);
                        },
                        isDark: false,
                        labelText: 'Nome Completo',
                        descriptionText: 'Nome',
                      ),
                      CustomTextField(
                        formKey: formKeys[1],
                        keyboardType: TextInputType.number,
                        validator: (phone) {
                          return ValidateInput.phone(phone);
                        },
                        controller: provider.signUpInput.phoneController,
                        inputFormatters: [
                          FilteringTextInputFormatter.digitsOnly,
                          TelefoneInputFormatter(),
                          LengthLimitingTextInputFormatter(100)
                        ],
                        isDark: false,
                        labelText: 'Celular',
                        descriptionText: 'Celular',
                      ),
                      CustomTextField(
                        formKey: formKeys[2],
                        keyboardType: TextInputType.emailAddress,
                        textCapitalization: TextCapitalization.none,
                        validator: (email) {
                          return ValidateInput.email(email);
                        },
                        controller: provider.signUpInput.emailController,
                        inputFormatters: [LengthLimitingTextInputFormatter(50)],
                        isDark: false,
                        labelText: 'E-mail',
                        descriptionText: 'E-mail',
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
                      onPressedCallback: provider.signup,
                      isValid: () => formKeys[0].currentState!.validate() &&
                          formKeys[1].currentState!.validate() && formKeys[2].currentState!.validate(),
                      text: 'Criar conta'
                  ),

                ]
            )
        );
      },
    );
  }
}