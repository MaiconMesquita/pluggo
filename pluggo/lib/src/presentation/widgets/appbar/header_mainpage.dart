import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:provider/provider.dart';

import '../../../config/routes/app_routes.dart';
import '../../../presentation/provider/theme_provider.dart';
import 'package:flutter/material.dart';
import '../../../provider/user_provider.dart';
import '../../styles/spacings.dart';

class HeaderMainpage extends StatelessWidget {
  final String? userName;
  final Color? color;
  final String? subtitle;
  final bool? closeButton;
  final VoidCallback? notificationButton;
  final bool? pageReturn;
  final double? topPadding;
  final VoidCallback? configButton;

  const HeaderMainpage({
    super.key,
    this.userName = '',
    this.color,
    this.pageReturn = false,
    this.topPadding = 0,
    this.closeButton = true,
    this.notificationButton,
    this.subtitle,
    this.configButton,
  });

  @override
  Widget build(BuildContext context) {
    final Color effectiveColor = color ?? ThemeProvider.of(context).appColors.white;
    UserProvider userProvider = Provider.of<UserProvider>(context, listen: true);


    return LayoutBuilder(builder: (context, constraints) {
      return SafeArea(
        child: Container(
          alignment: Alignment.center,
          //color: ThemeProvider.of(context).themeColors.background,
          padding: EdgeInsets.only(
              top: HeightSpacing.small,
              right: WidthSpacing.medium,
              left: WidthSpacing.medium
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              userProvider.firstName.isNotEmpty
              ? Row(
                children: [
                  Text(
                    'Olá, ',
                    style: TextStyle(
                      fontSize: 25,
                      color: color ?? ThemeProvider.of(context).appColors.primary,
                      fontFamily: 'Gilroy',
                      fontWeight: FontWeight.w300,
                    ),
                  ),
                  Text(
                    userProvider.firstName,
                    style: TextStyle(
                      fontSize: 25,
                      color: color ?? ThemeProvider.of(context).appColors.primary,
                      fontFamily: 'Gilroy',
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              )
              : Row(
                children: [
                  Text(
                    "pluggo",
                    style: TextStyle(
                      fontSize: 25,
                      color: color?? ThemeProvider.of(context).appColors.primary,
                      fontFamily: 'Gilroy',
                      fontWeight: FontWeight.w300,
                    ),
                  ),
                ],
              ),
              Row(
                children: [
                  SizedBox(width: 10),
                  Container(
                    width: 32, // largura total do botão
                    height: 32, // altura total do botão
                    decoration: BoxDecoration(
                      color: ThemeProvider.of(context).appColors.primary,
                      shape: BoxShape.circle,
                    ),
                    child: IconButton(
                      padding: EdgeInsets.zero,
                      icon: Icon(
                        Icons.edit,
                        size: 20,
                        color: ThemeProvider.of(context).appColors.white, // substitua pela cor desejada ou remova para usar a cor padrão
                      ),
                      onPressed: () {
                      },
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      );
    });
  }
}