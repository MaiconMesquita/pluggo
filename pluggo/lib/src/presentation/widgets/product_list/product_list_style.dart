
import 'package:flutter/material.dart';
import 'package:pluggo/src/infra/dto/product_dto.dart';
import 'package:pluggo/src/provider/style/home_provider.dart';
import 'package:provider/provider.dart';
import '../../../domain/value_object/date.dart';
import '../../controller/scroll_position_controller.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class ProductListWidget extends StatelessWidget {
  final int? id;
  const ProductListWidget({super.key,this.id});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<ProductProvider>(context, listen: true);
    final ScrollPositionController scrollController = ScrollPositionController();

    return Column(
        children: [
          Expanded(
              child: provider.products.isEmpty
                  ? Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    'Nenhuma compra efetuada...',
                    style: AppTypography.headlineWhite(context),
                  )
                ],
              )
                  :
              Scrollbar(
                  trackVisibility: true,
                  thumbVisibility: true,
                  thickness: WidthSpacing.custom(8),
                  radius: Radius.circular(WidthSpacing.custom(8)),
                  interactive: true,
                  controller: scrollController.scrollController,
                  child: ListView.builder(
                    controller: scrollController.scrollController,
                    itemCount: provider.products.length + 1, // Conta um item a mais para o "Carregar mais"
                    itemBuilder: (context, index) {
                      if (index ==
                          provider.products.length) {
                        // Exibe o carregamento ou o botão "Carregar mais" no final da lista
                        return  SizedBox.shrink();
                      } else {
                        final product = provider.products[index];
                        final products = provider.products;
                        return Column(
                          mainAxisAlignment: MainAxisAlignment.start,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            GestureDetector(
                              onTap: () async =>  {
                                //appRouter.push(DetailedStatementRoute(hasId: statement.hash))
                              },
                              child: Container(
                                padding: EdgeInsets.symmetric(vertical: HeightSpacing.small),
                                child: SizedBox(
                                  height: HeightSpacing.custom(45) + 32,
                                  child: Row(
                                    children: [
                                      SizedBox(
                                        width: HeightSpacing.custom(25) + 25,
                                        height: HeightSpacing.custom(25) + 25,
                                        child: ClipOval(
                                          child: Container(
                                              decoration: BoxDecoration(
                                                color: Colors.blue,
                                              ),
                                              child: SizedBox.shrink()
                                          ),
                                        ),
                                      ),
                                      SizedBox(width: WidthSpacing.smallMedium),
                                      Expanded(
                                        child: Column(
                                          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Flexible(
                                              child: Text(
                                                product.name,
                                                maxLines: 1,
                                                overflow: TextOverflow.ellipsis,
                                                style: AppTypography.bodyTitleBlack(context),
                                              ),
                                            ),
                                            Text(
                                                'próxima compra:',
                                                style: AppTypography.bodyBlack(context)),
                                          ],
                                        ),
                                      ),
                                      Container(width: WidthSpacing.extraSmall),
                                      Expanded(
                                          child: Column(
                                              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                              crossAxisAlignment: CrossAxisAlignment.end,
                                              children: [
                                                Flexible(
                                                  child: Text(product.value
                                                      .valueRealWithCents,
                                                      style: AppTypography.bodyBold(
                                                          context)),
                                                ), Text(
                                                    Date(DateTime.parse(product.reminderDate)).toFull,
                                                    style: AppTypography.bodyBlack(context))

                                              ]
                                          )
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            ),
                          ],
                        );
                      }
                    },
                    padding: EdgeInsets.only(bottom: HeightSpacing.medium, right: HeightSpacing.medium),
                  )))]);
  }
}