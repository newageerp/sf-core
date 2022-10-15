import { StatusWidget } from '@newageerp/v3.widgets.status-widget';
import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { useTranslation } from 'react-i18next';

interface Props {
  fieldKey: string;
}

export default function BoolRoColumn(props: Props) {
  const { t } = useTranslation();

  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <StatusWidget color={value ? "teal" : "slate"}>{value ? t("Taip") : t("Ne")}</StatusWidget>
  )
}
