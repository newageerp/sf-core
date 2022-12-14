import { FieldSelect } from "@newageerp/v3.bundles.form-bundle";
import React, { Fragment } from "react";
import { useTranslation } from "react-i18next";
import { useTemplatesLoader } from "@newageerp/v3.templates.templates-core";

interface Props {
  fieldKey: string;
}

export default function BoolEditableField(props: Props) {
  const { t } = useTranslation();
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey] ? 10 : 20;
  const updateValue = (e: any) => updateElement(props.fieldKey, e === 10);

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
