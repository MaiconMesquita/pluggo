import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:pluggo/src/presentation/layout/layout_list.dart';
import 'package:pluggo/src/presentation/widgets/appbar/header_default.dart';
import 'package:pluggo/src/presentation/widgets/product_list/global_product_list_style.dart';
import 'package:provider/provider.dart';

import '../../config/colors.dart';
import '../../config/theme.dart';
import '../../domain/enum/color_type.dart';
import '../../infra/dto/product_dto.dart';
import '../../infra/dto/users_dto.dart';
import '../../provider/user_provider.dart';
import '../layout/layout_main.dart';
import '../provider/main_provider.dart';
import '../provider/theme_provider.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_mainpage.dart';
import '../widgets/button/custom_loading_button.dart';
import '../widgets/inputs/custom_textfield.dart';
import '../widgets/product_list/product_list_style.dart';
import '../widgets/product_list/users_list_style.dart';

@RoutePage()
class UsersListScreen extends StatefulWidget {
  const UsersListScreen({super.key});

  @override
  State<UsersListScreen> createState() => _UsersListScreenState();
}

class _UsersListScreenState extends State<UsersListScreen> {

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<UsersProvider>(context, listen: true);
    final formKeys = List.generate(1, (index) => GlobalKey<FormState>());

    return ThemeProvider(
      theme: defaultTheme,
      child: Consumer<MainProvider>(
        builder: (context, provider, child) {
          return LayoutList(
            appBar: const HeaderDefault(),
            isDarkBackground: false,
            isLoading: provider.isLoading,
            children: [
                SizedBox(height: HeightSpacing.medium),
          Column(
                    children: [
                      CustomTextField(
                        formKey: formKeys[0],
                        keyboardType: TextInputType.text,
                        controller: provider.userNameController,
                        inputFormatters: [
                        ],
                        labelText: 'Nome do usuário',
                        descriptionText: 'Nome',
                      ),
                      CustomRoundedLoadingButton(
                          controller: provider.btnController,
                          color: ColorType.secondary,
                          isValid: () => true,
                          onPressedCallback: () async
                          {
                            await provider.userslList();
                          },
                          text: 'Gerar produto'),
                      SizedBox(height: HeightSpacing.medium),
              Text('data'),
                      Container(
                        padding: EdgeInsets.all(HeightSpacing.smallMedium),
                        height: HeightSpacing.custom(400),
                        decoration: BoxDecoration(
                          color: ThemeProvider.of(context).appColors.secondary,
                          borderRadius: BorderRadius.circular(HeightSpacing.custom(20)),
                        ),
                        child: UsersListWidget(),
                      ),
                    ],
                  ),
              ],
          );
        },
      ),
    );
  }
}
