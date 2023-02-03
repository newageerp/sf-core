import React, { Fragment, useState } from 'react'
import { Input } from "@newageerp/ui.form.base.form-pack";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { buildQsDictionary } from "../list/ListUtils"
import { useTranslation } from 'react-i18next';
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';

interface Props {
  fields: any[],
}

export default function ListToolbarQs(props: Props) {
  const { t } = useTranslation();

  const { data: tData } = useTemplatesLoader();

  const [value, setValue] = useState(tData?.defaults?.quickSearch);
  const updateValue = (e: any) => setValue(e.target.value);

  const onBlur = () => {
    if (tData.onAddExtraFilter) {
      tData.onAddExtraFilter(
        '__qs',
        buildQsDictionary(
          props.fields,
          value.trim(),
        )
      );
    }
  };

  const handleKeyDown = (event: any) => {
    if (event.key === 'Enter') {
      onBlur();
    }
  };

  return <Fragment>
    <Input
      iconName={"search"}
      className="tw3-w-full tw3-max-w-[150px] tw3-min-w-[150px] md:tw3-max-w-[500px] md:tw3-min-w-[300px]"
      value={value}
      onChange={updateValue}
      onBlur={onBlur}
      onKeyDown={handleKeyDown}
      placeholder={t('Search')}
    />
    <ToolbarButton
      iconName='arrows-rotate'
      onClick={tData.reloadData}
      loading={tData.reloading}
    />
  </Fragment>;
}
