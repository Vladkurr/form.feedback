<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
?>
<div class="modal" id="js--modal-oreder" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal__form">
        <div class="modal-content">
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                <svg>
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/img/svgsprite/sprite.symbol.svg#icon-close"></use>
                </svg>
            </button>
            <div class="modal-body">
                <div class="h3">Cвяжитесь с нами, чтобы заказать проект</div>
                <form id="<?= $arParams["TOKEN"] ?>" class="mform mform__bgwhite" novalidate>
                    <div class="mform__row">
                        <div class="mform__item col-12">
                            <input class="mform__input" type="text" placeholder="Ваше имя" autocomplete="off"
                                   name="NAME"/>
                            <div class="mform__text__error" style="display: none">Заполните имя</div>
                        </div>
                        <div class="mform__item col-12">
                            <input class="mform__input" id="js--input-phone2" type="tel"
                                   placeholder="Ваш телефон" autocomplete="off" name="PHONE"/>
                            <div class="mform__text__error" style="display: none">Введите корректный номер телефона</div>
                        </div>
                        <div class="mform__item col-12">
                            <input class="mform__input" type="email" placeholder="Ваш е-mail"
                                   autocomplete="off" name="MAIL"/>
                            <div class="mform__text__error" style="display: none">Введите корректный e-mail</div>
                        </div>
                        <div class="mform__item col-12">
                            <button class="mbtn mbtn__primary mbtn__wicon mbtn__middle w-100" type="submit">
                                <span>
                                    Отправить
                                    <i>
                                        <svg>
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/img/svgsprite/sprite.symbol.svg#arrow-next"></use>
                                        </svg>
                                    </i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="invite__des">
                        <p>Нажимая на кнопку, Вы соглашаетесь с <a href="/policy/" target="_blank">политикой
                                конфиденциальности</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        new initForm("<?= $component->getName() ?>", <?= json_encode($arParams) ?>)
    });
</script>
