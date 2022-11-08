import { StatusWidget } from '@newageerp/v3.bundles.widgets-bundle';
import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { useTranslation } from 'react-i18next';

interface Props {
  fieldKey: string;
}

export default function ColorRoField(props: Props) {
  const { t } = useTranslation();

  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <StatusWidget color={value}>{t(value)}</StatusWidget>
  )
}
