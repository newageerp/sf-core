import React, { Fragment, useState } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { FieldSelect, FieldTextarea } from '@newageerp/v3.bundles.form-bundle';
import { showSuccessNotification } from '../../navigation/NavigationComponent';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { useTranslation } from 'react-i18next';

interface Props {
  fieldKey: string;
  schema: string;
}

export default function BoolEditableColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  const [value, setValue] = useState(element ? element[props.fieldKey] : "");
  const updateValue = (e: any) => {
    setValue(e.target.value);
    saveElement();
  }

  const { t } = useTranslation();

  const [doSave] = OpenApi.useUSave(
    props.schema
  );

  if (!element) {
    return <Fragment />;
  }

  const saveElement = () => {
    doSave(
      {
        [props.fieldKey]: value,
      },
      element.id
    ).then(() => {
      showSuccessNotification("Saved");
    });
  };

  const options = [
    {
      value: 10,
      label: t("Yes"),
    },
    {
      value: 20,
      label: t("No"),
    },
  ];

  return (
    <FieldSelect
      value={value}
      onChange={updateValue}
      options={options}
      className="tw3-w-40"
      icon={element[props.fieldKey] ? "toggle-large-on" : "toggle-large-off"}
    />
  );
}
