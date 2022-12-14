import React, { Fragment } from 'react'
import { useTranslation } from 'react-i18next';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { StatusWidget } from '@newageerp/v3.bundles.widgets-bundle';
import { FieldSelect } from '@newageerp/v3.bundles.form-bundle';

interface Props {
  fieldKey: string;
}

export default function ColorEditableField(props: Props) {
  const { t } = useTranslation();
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  const colors = [
    "red", "purple", "blue", "sky", "lime", "amber", "orange", "pink", "teal", "slate", "bronze", "black"
  ];

  const options = colors.map(color => {
    return (
      {
        value: color,
        // @ts-ignore
        label: <StatusWidget color={color}>{t(color)}</StatusWidget>
      }
    )
  });

  return (
    <FieldSelect
      value={value}
      onChange={updateValue}
      options={options}
      className="tw3-w-40"
    />
  )
}
