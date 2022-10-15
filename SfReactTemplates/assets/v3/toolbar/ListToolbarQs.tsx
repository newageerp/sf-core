import React, { useState } from 'react'
import { Input } from "@newageerp/ui.form.base.form-pack";
import { useTemplateLoader } from '../templates/TemplateLoader';
import { buildQsDictionary } from "../list/ListUtils"
import { useTranslation } from 'react-i18next';

interface Props {
  fields: any[],
}

export default function ListToolbarQs(props: Props) {
  const { t } = useTranslation();

  const { data: tData } = useTemplateLoader();

  const [value, setValue] = useState(tData?.defaults?.quickSearch);
  const updateValue = (e: any) => setValue(e.target.value);

  const onBlur = () => {
    if (tData.onAddExtraFilter) {
      tData.onAddExtraFilter(
        '__qs',
        buildQsDictionary(
          props.fields,
          value,
        )
      );
    }
  };

  const handleKeyDown = (event: any) => {
    if (event.key === 'Enter') {
      onBlur();
    }
  };

  return <Input
    iconName={"search"}
    className="tw3-w-full tw3-max-w-[500px] tw3-min-w-[300px]"
    value={value}
    onChange={updateValue}
    onBlur={onBlur}
    onKeyDown={handleKeyDown}
    placeholder={t('Search')}
  />;
}
